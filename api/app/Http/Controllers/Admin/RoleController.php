<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Validator;
use Log;

class RoleController extends Controller
{
	function page(Request $request)
	{
        $page = request('page', 1);
        $limit = request('limit', 10); 
        $sort = request('sort', 'id');
        $order = request('order', 'desc');

		$q = new Role();

		!empty($request->name) && $q = $q->where('name', 'like', '%'.$request->name.'%');
		!empty($request->code) && $q = $q->where('name', 'like', '%'.$request->code.'%');
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

	function lists(Request $request)
	{
        $q = Role::all();

        return $this->success('获取成功', $q);
	}

	function create(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'code' => 'required|unique:roles,code',
			'name' => 'required|unique:roles,name',
		], [
			'code.required' => '角色标识不能为空',
			'code.unique'   => '角色标识不能重复',
			'name.required' => '角色名不能为空',
			'name.unique'   => '角色名不能重复',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

		$params = [
			'code' => $request->code,
			'name' => $request->name,
			'note' => empty($request->note) ? '' : $request->note,
		];

		Role::create($params);

        return $this->success('创建成功');
	}

	function detail(Request $request)
	{
	}

	function update(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			'code' => 'required|unique:roles,code,'.$request->id.',id',
			'id'   => 'required|exists:roles,id',
			'name' => 'required|unique:roles,name,'.$request->id.',id',
		], [
			'code.required' => '角色标识不能为空',
			'code.unique'   => '角色标识不能重复',
			'name.required' => '角色名不能为空',
			'name.unique'   => '角色名不能重复',
			'id'            => '请求错误',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

		$params = [
			'code'     => $request->code,
			'name'     => $request->name,
			'note'     => empty($request->note) ? '' : $request->note,
		];

		Role::where('id', $request->id)
			->update($params);;

        return $this->success('创建成功');

	}

	function remove(Request $request)
	{
		$id = $request->id;

		if(!empty($id)){
			Role::where('id', $id)->delete();

			return $this->success('操作成功');
		}

		return $this->fail('操作失败');
	}

	function removeBatch(Request $request)
	{
		$ids = $request->ids;

		if(!empty($ids)){
			Role::whereIn('id', $ids)->delete();

			return $this->success('操作成功');
		}

		return $this->fail('操作失败');
	}

	function updateStatus(Request $request)
	{
	}
}
