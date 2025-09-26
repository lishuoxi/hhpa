<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\User;
use App\Models\LoginRecord;

class LoginRecordController extends Controller
{
	function page(Request $request)
	{
        $page = request('page', 1);
        $limit = request('limit', 10);

        $q = new LoginRecord();

		!empty($request->createTimeStart) && $q = $q->where('created_at', '>=', $request->createTimeStart);
		!empty($request->createTimeEnd) && $q = $q->where('created_at', '<', $request->createTimeEnd);
		!empty($request->username) && $q = $q->where('username', 'like', '%'.$request->username.'%');

        $total = (clone $q)->count();
        $total_page = floor(($total-1)/$limit)+1;

        $offset = ($page-1)*$limit;
        $lists = $q->offset($offset)->limit($limit)->orderby('id', 'desc')->get();

        return $this->success('获取成功', [
            'count'     => $total,
            'totalPage' => $total_page,
            'list'      => $lists,
            'limit'     => $limit,
            'page'      => $page,
        ]);
	}

	function lists(Request $request)
	{
        $q = LoginRecord::all();

        return $this->success('获取成功', $q);
	}
}
