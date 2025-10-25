<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
use App\Models\Channel;
use Validator;
use App\Services\AuthAdmin;
use App\Services\Http;
use App\Services\AccountQueue;
use App\Services\PageService;
use Log;

class AccountController extends Controller
{
    function page(Request $request)
    {
        $page = request('page', 1);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'desc');

        $q = Account::with(['account_owner', 'account_type']);

        $admin = AuthAdmin::admin();
        if ($admin->role_id == 4) {
            $q = $q->where('account_owner_id', $admin->id);
        }

        !empty($request->account_owner_id) && $q = $q->where('account_owner_id', $request->account_owner_id);
        !empty($request->name) && $q = $q->where('name', 'like', '%'.$request->name.'%');
        !empty($request->account_type_id) && $q = $q->where('account_type_id', $request->account_type_id);

        $total = (clone $q)->count();
        $total_page = floor(($total-1)/$limit)+1;

        $offset = ($page-1)*$limit;
        $lists = $q->offset($offset)->limit($limit)->orderby($sort, $order)->get();

        return $this->success('获取成功', [
            'count'     => $total,
            'totalPage' => $total_page,
            'list'      => $lists,
            'limit'     => $limit,
            'page'      => $page,
        ]);
    }

    // 9030 page bridge APIs
    function pageGet(Request $request)
    {
        $validator = Validator::make(request()->all(), [ 'id' => 'required' ]);
        if ($validator->fails()) {
            return $this->fail($validator->errors()->first());
        }
        $id = (string)$request->id;
        $svc = app(PageService::class);
        Log::info('page.get.req', ['id' => $id, 'params' => request()->all()]);
        $res = $svc->getPage($id);
        $data = ['raw' => $res, 'data' => $this->tryJson($res)];
        Log::info('page.get.res', $data);
        return $this->success('ok', $data);
    }

    function pageSetNotify(Request $request)
    {
        $validator = Validator::make(request()->all(), [ 'id' => 'required' ]);
        if ($validator->fails()) {
            return $this->fail($validator->errors()->first());
        }
        $id = (string)$request->id;
        $notify = (string)($request->notify ?? '');
        if (empty($notify)) {
            if (function_exists('route')) {
                try { $notify = route('test_trade_notify_url'); } catch (\Throwable $e) { $notify = url('/test/trade/notify_url'); }
            } else {
                $notify = url('/test/trade/notify_url');
            }
        }
        $svc = app(PageService::class);
        Log::info('page.set_notify.req', ['id' => $id, 'notify' => $notify]);
        $res = $svc->setNotify($id, $notify);
        $data = ['raw' => $res, 'data' => $this->tryJson($res)];
        Log::info('page.set_notify.res', $data);
        return $this->success('ok', $data);
    }

    function pageStart(Request $request)
    {
        $validator = Validator::make(request()->all(), [ 'id' => 'required' ]);
        if ($validator->fails()) {
            return $this->fail($validator->errors()->first());
        }
        $id = (string)$request->id;
        $svc = app(PageService::class);
        Log::info('page.start.req', ['id' => $id]);
        $res = $svc->startPage($id);
        $data = ['raw' => $res, 'data' => $this->tryJson($res)];
        Log::info('page.start.res', $data);
        return $this->success('ok', $data);
    }

    function pageDel(Request $request)
    {
        $validator = Validator::make(request()->all(), [ 'id' => 'required' ]);
        if ($validator->fails()) {
            return $this->fail($validator->errors()->first());
        }
        $id = (string)$request->id;
        $svc = app(PageService::class);
        $res = $svc->delPage($id);
        return $this->success('ok', ['raw' => $res, 'data' => $this->tryJson($res)]);
    }

    private function tryJson($str)
    {
        if (!is_string($str)) return $str;
        $d = json_decode($str, true);
        return json_last_error() === JSON_ERROR_NONE ? $d : null;
    }

    // 获取扫码登录的二维码内容（初始化：先 getPage 再 setNotify）
    function loginQrContent(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'id' => 'required|exists:accounts,id',
        ], [
            'id.required' => '支付码不存在',
            'id.exists'   => '支付码不存在',
        ]);

        if ($validator->fails()) {
            return $this->fail($validator->errors()->first());
        }
        $id = (string)$request->id;
        // 初始化：预拉取一次页面并设置通知，再返回 page_id 供前端轮询
        $svc = app(PageService::class);
        Log::info('loginQr.init.get.req', ['id' => $id]);
        $getRes = $svc->getPage($id);
        Log::info('loginQr.init.get.res', ['raw' => $getRes, 'data' => $this->tryJson($getRes)]);

        $notify = '';
        if (function_exists('route')) {
            try { $notify = route('test_trade_notify_url'); } catch (\Throwable $e) { $notify = url('/test/trade/notify_url'); }
        } else { $notify = url('/test/trade/notify_url'); }
        Log::info('loginQr.init.set.req', ['id' => $id, 'notify' => $notify]);
        $setRes = $svc->setNotify($id, $notify);
        Log::info('loginQr.init.set.res', ['raw' => $setRes, 'data' => $this->tryJson($setRes)]);

        return $this->success('获取成功', ['page_id' => $id]);
    }

    // 确认登录，更新登录状态与时间
    function loginConfirm(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'id' => 'required|exists:accounts,id',
        ], [
            'id.required' => '支付码不存在',
            'id.exists'   => '支付码不存在',
        ]);

        if ($validator->fails()) {
            return $this->fail($validator->errors()->first());
        }

        $account = Account::where('id', $request->id)->first();
        if (!$account) {
            return $this->fail('支付码不存在');
        }

        $account->update([
            'is_logged_in' => 1,
            'login_time'   => now(),
        ]);

        return $this->success('操作成功');
    }

    function lists(Request $request)
    {
        $q = new Account();
        $q = $q->with(['account_owner', 'account_type']);

        !empty($request->account_owner_id) && $q = $q->where('account_owner_id', $request->account_owner_id);
        !empty($request->name) && $q = $q->where('name', 'like', '%'.$request->name.'%');
        !empty($request->account_type_id) && $q = $q->where('account_type_id', $request->account_type_id);

        $lists = $q->get();

        return $this->success('获取成功', $lists);
    }

    function detail(Request $request)
    {
        $id = $request->id;

        $account = Account::find($id);

        return $this->success('获取成功', $account);
    }

    function create(Request $request)
    {
        $validator = Validator::make(request()->all(), [  
            'name'             => 'required',
            'account_type_id'  => 'required',
            //'account_owner_id' => 'required',
        ], [
            'name.required'             => '请填写名称',
            'account_type_id.required'  => '请选择支付码类型',
            //'account_owner_id.required' => '请选择所属码商',
        ]);

        if($validator->fails()){
            return $this->fail($validator->errors()->first());
        }
        $admin = AuthAdmin::admin();
        if(empty($admin)){
            return $this->fail('操作失败');
        }

        $account_owner_id = 0;
        if($admin->role_id == 1){
            if(empty($request->account_owner_id)){
                return $this->fail('请选择支付码所属码商');
            }
            $account_owner_id = $request->account_owner_id;
        }
        else {
            $account_owner_id = $admin->id;
        }

        $params                 = [
            'name'             => $request->name,
            'account_type_id'  => $request->account_type_id,
            'account_owner_id' => $account_owner_id,
            //'account_owner_id' => $request->account_owner_id,
            'param1'           => empty($request->param1) ? '' : $request->param1,
            'param2'           => empty($request->param2) ? '' : $request->param2,
            'param3'           => empty($request->param3) ? '' : $request->param3,
            'param4'           => empty($request->param4) ? '' : $request->param4,
            'param5'           => empty($request->param5) ? '' : $request->param5,
            'param6'           => empty($request->param6) ? '' : $request->param6,
            'note'             => empty($request->note) ? '' : $request->note,
            'amount_day_limit' => empty($request->amount_day_limit) ? 0 : $request->amount_day_limit,
            'times_day_limit'  => empty($request->times_day_limit) ? 0 : $request->times_day_limit,
            'amount_max_limit' => empty($request->amount_max_limit) ? 0 : $request->amount_max_limit,
            'amount_min_limit' => empty($request->amount_min_limit) ? 0 : $request->amount_min_limit,
        ];

        $account = Account::create($params);

        AccountQueue::updateByAccount($account);

        return $this->success('创建成功');
    }

    function update(Request $request)
    {
        $validator = Validator::make(request()->all(), [  
            'id'               => 'required|exists:accounts,id',
            'name'             => 'required',
            'account_type_id'  => 'required',
            //'account_owner_id' => 'required',
        ], [
            'id.required'               => '数据不存在',
            'id.exists'                 => '数据不存在',
            'name.required'             => '请填写名称',
            'account_type_id.required'  => '请选择支付码类型',
            //'account_owner_id.required' => '请选择所属码商',
        ]);

        if($validator->fails()){
            return $this->fail($validator->errors()->first());
        }

        $admin = AuthAdmin::admin();
        if(empty($admin)){
            return $this->fail('操作失败');
        }

        $account_owner_id = 0;
        if($admin->role_id == 1){
            if(empty($request->account_owner_id)){
                return $this->fail('请选择支付码所属码商');
            }
            $account_owner_id = $request->account_owner_id;
        }
        else {
            //if($request->account_owner_id != $admin->id){
                //return $this->fail('操作失败');
            //}
            $account_owner_id = $admin->id;
        }

        $params = [
            'name'             => $request->name,
            'account_type_id'  => $request->account_type_id,
            'account_owner_id' => $account_owner_id,
            'param1'           => empty($request->param1) ? '' : $request->param1,
            'param2'           => empty($request->param2) ? '' : $request->param2,
            'param3'           => empty($request->param3) ? '' : $request->param3,
            'param4'           => empty($request->param4) ? '' : $request->param4,
            'param5'           => empty($request->param5) ? '' : $request->param5,
            'param6'           => empty($request->param6) ? '' : $request->param6,
            'note'             => empty($request->note) ? '' : $request->note,
            'amount_day_limit' => empty($request->amount_day_limit) ? 0 : $request->amount_day_limit,
            'times_day_limit'  => empty($request->times_day_limit) ? 0 : $request->times_day_limit,
            'amount_max_limit' => empty($request->amount_max_limit) ? 0 : $request->amount_max_limit,
            'amount_min_limit' => empty($request->amount_min_limit) ? 0 : $request->amount_min_limit,
        ];

        $account = Account::where('id', $request->id)->first();
        $account->update($params);

        AccountQueue::updateByAccount($account);

        return $this->success('操作成功');
    }

    function remove(Request $request)
    {
        $id = $request->id;

        if(!empty($id)){
            Account::where('id', $id)->delete();

            return $this->success('操作成功');
        }

        return $this->fail('操作失败');
    }

    function removeBatch(Request $request)
    {
        $ids = $request->ids;

        if(!empty($ids)){
            Account::whereIn('id', $ids)->delete();

            return $this->success('操作成功');
        }

        return $this->fail('操作失败');
    }

    function existence(Request $request)
    {
        $validator = Validator::make(request()->all(), [  
            'field' => 'required',
            'value' => 'required',
        ], [
            'field.required' => '字段不存在',
            'value.required' => '值不存在',
        ]);

        if($validator->fails()){
            return $this->fail($validator->errors()->first());
        }

        $q = Account::where($request->field, $request->value);

        if(!empty($request->id)){
            $q = $q->where('id', '<>', $request->id);
        }

        $account = $q->first();

        if(empty($account)){
            return $this->fail('不存在');
        }

        return $this->success('存在');
    }

    function updateStatus(Request $request)
    {
        $validator = Validator::make(request()->all(), [  
            'id'     => 'required',
            'status' => 'required',
        ], [
            'id.required'     => '支付码不存在',
            'status.required' => '数据错误',
        ]);

        if($validator->fails()){
            Log::info($validator->errors()->first());
            return $this->fail($validator->errors()->first());
        }

        $account = Account::where('id', $request->id)->first();
        if(!empty($account)){
            $account->update(['status' => $request->status]);

            \Log::info('开始更新');

            AccountQueue::updateByAccount($account);
        }

        return $this->success('操作成功');
    }

    function createTest(Request $request)
    {
        $validator = Validator::make(request()->all(), [  
            'id'         => 'required|exists:accounts,id',
            'channel_id' => 'required|exists:channels,id',
            'amount'     => 'required',
        ], [
            'id.required'         => '支付码不存在',
            'channel_id.required' => '请选择通道',
            'amount.required'     => '数据错误',
        ]);

        if($validator->fails()){
            Log::info($validator->errors()->first());
            return $this->fail($validator->errors()->first());
        }

        $merchant = User::where('role_id', 2)->orderBy('id')->first();
        if(empty($merchant)){
            return $this->fail('失败');
        }

        $channel = Channel::where('id', $request->channel_id)->first();

        Http::postRequest(route('trade-create'), [
            'merchant_id' => $merchant->merchant_id,
            'amount'      => $request->amount,
            'pay_type'    => $channel->code,
            'notify_url'  => route('trade-notify', ['trade_id'=>1]),
        ]);
        
        return $this->success('操作成功');
    }
}
