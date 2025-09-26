<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cashflow;
use App\Services\AuthAdmin;
use Rap2hpoutre\FastExcel\FastExcel;
use Storage;

class CashflowController extends Controller
{
	function page(Request $request)
	{
        $page = request('page', 1);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'desc');

        $admin = AuthAdmin::admin();

        $q = new Cashflow();

        if($admin->role_id != 1){
            $q = $q->where('user_id', $admin->id);
        }
        else {
            $q = $q->with(['user']);
        }

		!empty($request->user_id) && $q = $q->where('user_id', $request->user_id);
		!empty($request->started) && $q = $q->where('created_at','>=', $request->started);
		!empty($request->ended) && $q = $q->where('created_at', '<=', $request->ended);
        !empty($request->username) && $q = $q->whereHas('user', function($q)use($request){
            $q->where('username', 'like', '%'.$request->username.'%');
        });
		!empty($request->note) && $q = $q->where('note', 'like', '%'.$request->note.'%');

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

    function export(Request $request)
    {
        $sort = request('sort', 'id');
        $order = request('order', 'desc');

        $admin = AuthAdmin::admin();

        $q = new Cashflow();

        if($admin->role_id != 1){
            $q = $q->where('user_id', $admin->id);
        }

		!empty($request->user_id) && $q = $q->where('user_id', $request->user_id);
		!empty($request->started) && $q = $q->where('created_at','>=', $request->started);
		!empty($request->ended) && $q = $q->where('created_at', '<=', $request->ended);
        !empty($request->username) && $q = $q->whereHas('user', function($q){
            $q->where('username', 'like', '%'.$request->username.'%');
        });
		!empty($request->note) && $q = $q->where('note', 'like', '%'.$request->note.'%');

        $total = (clone $q)->get();

        $filename = create_id().'.xlsx';
        $path = Storage::disk('public')->path($filename);

        (new FastExcel($total))->export($path, function($row){
            return  [
                //'id'         => $row->id,
                '流水号'     => $row->cashflow_id,
                '变动前金额' => $row->daifu_amount_before,
                '变动后金额' => $row->daifu_amount_after,
                '变动金额'   => $row->daifu_amount,
                '备注'       => $row->note,
                '时间'       => $row->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return $this->success('', [
            //'url' => Storage::disk('public')->url($filename),
            'url' => route('index-download', ['filename'=>$filename]),
        ]);;
    }
}
