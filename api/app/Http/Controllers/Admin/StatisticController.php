<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trade;
use App\Models\Daifu;
use App\Models\Channel;
use App\Models\User;
use App\Models\Account;
use App\Models\TradeAccountOwner;
use Carbon\Carbon;
use DB;

class StatisticController extends Controller
{
	function index(Request $request)
	{
        $all = Trade::select(DB::raw('sum(`amount`) as amount_total'))->where('status', '支付完成')->get()->toArray();
        $all_daifu = Daifu::select(DB::raw('sum(`amount`) as amount_total'))->where('status', '处理成功')->get()->toArray();

        $yesterday = Trade::select(DB::raw('count(*) as num, sum(`amount`) as amount_total'))
            ->where('created_at', '>=', Carbon::yesterday())
            ->where('created_at', '<=', Carbon::today())
            ->where('status', '支付完成')
            ->get()->toArray();

        $yesterday_all = Trade::select(DB::raw('count(*) as num'))
            ->where('created_at', '>=', Carbon::yesterday())
            ->where('created_at', '<=', Carbon::today())
            ->get()->toArray();

        $today = Trade::select(DB::raw('count(*) as num, sum(`amount`) as amount_total'))
            ->where('created_at', '>=', Carbon::today())
            ->where('created_at', '<=', Carbon::tomorrow())
            ->where('status', '支付完成')
            ->get()->toArray();

        $today_all = Trade::select(DB::raw('count(*) as num'))
            ->where('created_at', '>=', Carbon::today())
            ->where('created_at', '<=', Carbon::tomorrow())
            ->get()->toArray();

        $today_daifu = Daifu::select(DB::raw('sum(`amount`) as amount_total'))
            ->where('status', '处理成功')
            ->where('created_at', '>=', Carbon::today())
            ->where('created_at', '<=', Carbon::tomorrow())
            ->get()->toArray();
        $yesterday_daifu = Daifu::select(DB::raw('sum(`amount`) as amount_total'))
            ->where('created_at', '>=', Carbon::yesterday())
            ->where('created_at', '<=', Carbon::today())
            ->where('status', '处理成功')
            ->get()->toArray();

        $today_success_rate  = ($today_all[0]['num']<1) ? '0' : ($today[0]['num']*100.0/$today_all[0]['num']);
        $yesterday_success_rate  = ($yesterday_all[0]['num']<1) ? '0' : ($yesterday[0]['num']*100.0/$yesterday_all[0]['num']);

        $today_success_rate = round($today_success_rate*100)*0.01;
        $yesterday_success_rate = round($yesterday_success_rate*100)*0.01;

        return $this->success('获取成功', [
          'total_amount'           => $all[0]['amount_total'],
          'total_daifu_amount'     => $all_daifu[0]['amount_total'],
          'today_amount'           => $today[0]['amount_total'],
          'today_num'              => $today[0]['num'],
          'today_success_rate'     => $today_success_rate,
          'yesterday_amount'       => $yesterday[0]['amount_total'],
          'yesterday_num'          => $yesterday[0]['num'],
          'yesterday_success_rate' => $yesterday_success_rate,
          'today_daifu'            => $today_daifu[0]['amount_total'],
          'yesterday_daifu'        => $yesterday_daifu[0]['amount_total'],
        ]);
	}

	function merchant(Request $request)
	{
        $q = Role::all();

        return $this->success('获取成功', $q);
	}

	function channel(Request $request)
	{
        $q = Trade::select(DB::raw('channel_id, count(*) as trade_count, sum(`amount`) as amount_total'))
            ->groupBy('channel_id');

		!empty($request->started) && $q = $q->where('created_at', '>=', $request->started);
		!empty($request->ended) && $q = $q->where('created_at', '<=', $request->ended);

        $all = (clone $q)->get()->toArray();
        $success = (clone $q)->where('status', '支付完成')->get()->toArray();

        $q2 = Trade::select(DB::raw('channel_id, count(*) as trade_count, sum(`amount`) as amount_total'))
            ->groupBy('channel_id')
            ->where('created_at', '>=', Carbon::today())
            ->where('created_at', '<=', Carbon::tomorrow());

        $all_today = (clone $q2)->get()->toArray();
        $success_today = (clone $q2)->where('status', '支付完成')->get()->toArray();

        $channels = Channel::all()->toArray();

        foreach($channels as $k=>$channel){
            foreach($all as $all_item){
                if($all_item['channel_id'] == $channel['id']){
                    $channels[$k]['all'] = $all_item;
                }
            }

            foreach($success as $success_item){
                if($success_item['channel_id'] == $channel['id']){
                    $channels[$k]['success'] = $success_item;
                }
            }

            foreach($all_today as $all_today_item){
                if($all_today_item['channel_id'] == $channel['id']){
                    $channels[$k]['all_today'] = $all_today_item;
                }
            }

            foreach($success_today as $success_today_item){
                if($success_today_item['channel_id'] == $channel['id']){
                    $channels[$k]['success_today'] = $success_today_item;
                }
            }
        }

        return $this->success('获取成功', $channels);
	}

	function accountOwner(Request $request)
	{
        $page = request('page', 1);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'desc');

        $q = new User();
        $q = $q->where('role_id', 4);

		!empty($request->name) && $q = $q->where('username', 'like', '%'.$request->name.'%');

        $total = (clone $q)->count();
        $total_page = floor(($total-1)/$limit)+1;
        $offset = ($page-1)*$limit;

        $account_owners = $q->offset($offset)->limit($limit)->orderby($sort, $order)->get()->toArray();
        $account_owner_ids = array_map(function($item) { 
                return $item['id'];
            }, $account_owners);

        $q = Trade::select(DB::raw('account_owner_id, count(*) as trade_count, sum(`amount`) as amount_total'))
            ->whereIn('account_owner_id', $account_owner_ids)
            ->groupBy('account_owner_id');

		!empty($request->started) && $q = $q->where('created_at', '>=', $request->started);
		!empty($request->ended) && $q = $q->where('created_at', '<=', $request->ended);

        $all = (clone $q)->get()->toArray();
        $success = (clone $q)->where('status', '支付完成')->get()->toArray();

        $q2 = Trade::select(DB::raw('account_owner_id, count(*) as trade_count, sum(`amount`) as amount_total'))
            ->whereIn('account_owner_id', $account_owner_ids)
            ->groupBy('account_owner_id')
            ->where('created_at', '>=', Carbon::today())
            ->where('created_at', '<=', Carbon::tomorrow());

        $all_today = (clone $q2)->get()->toArray();
        $success_today = (clone $q2)->where('status', '支付完成')->get()->toArray();

        $q3 = TradeAccountOwner::select(DB::raw('account_owner_id, level, sum(`amount`) as amount_total'))
            ->whereIn('account_owner_id', $account_owner_ids)
            ->groupBy('account_owner_id')
            ->groupBy('level');

		!empty($request->started) && $q3 = $q3->where('created_at', '>=', $request->started);
		!empty($request->ended) && $q3 = $q3->where('created_at', '<=', $request->ended);

        $profit_all = (clone $q3)->get()->toArray();

        $q4 = TradeAccountOwner::select(DB::raw('account_owner_id, level, sum(`amount`) as amount_total'))
            ->whereIn('account_owner_id', $account_owner_ids)
            ->groupBy('account_owner_id')
            ->groupBy('level')
            ->where('created_at', '>=', Carbon::today())
            ->where('created_at', '<=', Carbon::tomorrow());

        $profit_today = (clone $q4)->get()->toArray();

        foreach($account_owners as $k=>$account_owner){
            foreach($all as $all_item){
                if($all_item['account_owner_id'] == $account_owner['id']){
                    $account_owners[$k]['all'] = $all_item;
                }
            }

            foreach($success as $success_item){
                if($success_item['account_owner_id'] == $account_owner['id']){
                    $account_owners[$k]['success'] = $success_item;
                }
            }

            foreach($all_today as $all_today_item){
                if($all_today_item['account_owner_id'] == $account_owner['id']){
                    $account_owners[$k]['all_today'] = $all_today_item;
                }
            }

            foreach($success_today as $success_today_item){
                if($success_today_item['account_owner_id'] == $account_owner['id']){
                    $account_owners[$k]['success_today'] = $success_today_item;
                }
            }

            foreach($profit_all as $profit_all_item){
                if($profit_all_item['account_owner_id'] == $account_owner['id']){
                    $account_owners[$k][ 'level'.$profit_all_item['level'] ] = $profit_all_item['amount_total'];
                }
            }

            foreach($profit_today as $profit_today_item){
                if($profit_today_item['account_owner_id'] == $account_owner['id']){
                    $account_owners[$k][ 'today_level'.$profit_today_item['level'] ] = $profit_today_item['amount_total'];
                }
            }
        }

        return $this->success('获取成功', [
            'count'                => $total,
            'totalPage'            => $total_page,
            'list'                 => $account_owners,
            'limit'                => $limit,
            'page'                 => $page,
        ]);
	}

	function account(Request $request)
	{
        $page = request('page', 1);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'desc');

        $q = new Account();

		!empty($request->name) && $q = $q->where('name', 'like', '%'.$request->name.'%');

        $total = (clone $q)->count();
        $total_page = floor(($total-1)/$limit)+1;
        $offset = ($page-1)*$limit;

        $accounts = $q->offset($offset)->limit($limit)->orderby($sort, $order)->get()->toArray();
        $account_ids = array_map(function($item) { 
                return $item['id'];
            }, $accounts);

        $q = Trade::select(DB::raw('account_id, count(*) as trade_count, sum(`amount`) as amount_total'))
            ->whereIn('account_id', $account_ids)
            ->groupBy('account_id');

		!empty($request->started) && $q = $q->where('created_at', '>=', $request->started);
		!empty($request->ended) && $q = $q->where('created_at', '<=', $request->ended);

        $all = (clone $q)->get()->toArray();
        $success = (clone $q)->where('status', '支付完成')->get()->toArray();

        $q2 = Trade::select(DB::raw('account_id, count(*) as trade_count, sum(`amount`) as amount_total'))
            ->whereIn('account_id', $account_ids)
            ->groupBy('account_id')
            ->where('created_at', '>=', Carbon::today())
            ->where('created_at', '<=', Carbon::tomorrow());

        $all_today = (clone $q2)->get()->toArray();
        $success_today = (clone $q2)->where('status', '支付完成')->get()->toArray();

        foreach($accounts as $k=>$account){
            foreach($all as $all_item){
                if($all_item['account_id'] == $account['id']){
                    $accounts[$k]['all'] = $all_item;
                }
            }

            foreach($success as $success_item){
                if($success_item['account_id'] == $account['id']){
                    $accounts[$k]['success'] = $success_item;
                }
            }

        }

        return $this->success('获取成功', [
            'count'                => $total,
            'totalPage'            => $total_page,
            'list'                 => $accounts,
            'limit'                => $limit,
            'page'                 => $page,
        ]);
	}
}
