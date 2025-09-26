<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\User;
use Validator;
use Log;

class MenuController extends Controller
{
	function page(Request $request)
	{
        $page = request('page', 1);
        $limit = request('limit', 10);

        $q = new Menu();

        $total = (clone $q)->count();
        $total_page = floor(($total-1)/$limit)+1;

        $offset = ($page-1)*$limit;
        $lists = $q->offset($offset)->limit($limit)->orderby('roleId', 'desc')->get();

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
        $q = Menu::where('menuId', '>', 0);

		!empty($request->title) && $q = $q->where('title', 'like', '%'.$request->title.'%');
		!empty($request->path) && $q = $q->where('path', 'like', '%'.$request->path.'%');
		!empty($request->authority) && $q = $q->where('authority', 'like', '%'.$request->authority.'%');

		$q = $q->get();

        return $this->success('获取成功', $q);
	}

	function detail(Request $request)
	{
	}

	function update(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			//'authority' => 'required|unique:users,username',
			//'component' => 'required|unique:users,nickname',
			//'hide'    => 'required',
			//'icon' => 'required',
			'menuId' => 'required|exists:menus,menuId',
			//'path'   => 'required',
			'title'  => 'required',
		], [
			'title.required' => '菜单名不能为空',
			'title.path' => '菜单名路径',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

		$data = [
			'parentId'   => $request->parentId,
			'title'      => $request->title,
			'icon'       => request('icon'),
			'path'       => request('path', ''),
			'component'  => request('component', ''),
			'sortNumber' => request('sortNumber', 0),
			'authority'  => request('authority', ''),
			'target'     => request('target', '_self'),
			'hide'       => request('hide', '0'),
		];

		Menu::where('menuId', $request->menuId)->update($data);

        return $this->success('创建成功');
	}

	function remove(Request $request)
	{
		$id = $request->id;

		if(!empty($id)){
			Menu::where('menuId', $id)->delete();

			return $this->success('操作成功');
		}

		return $this->fail('操作失败');
	}

	function removeBatch(Request $request)
	{
	}

	function updateStatus(Request $request)
	{
	}

	function add(Request $request)
	{
		$validator = Validator::make(request()->all(), [  
			//'authority' => 'required|unique:users,username',
			//'component' => 'required|unique:users,nickname',
			//'hide'    => 'required',
			//'icon' => 'required',
			//'path' => 'required',
			'title' => 'required',
		], [
			'title.required' => '菜单名不能为空',
			//'title.path' => '菜单名路径',
		]);

		if($validator->fails()){
			Log::info($validator->errors()->first());
			return $this->fail($validator->errors()->first());
		}

		Menu::create([
			'parentId'   => $request->parentId,
			'title'      => $request->title,
			'icon'       => request('icon'),
			'path'       => request('path', ''),
			'component'  => request('component', ''),
			'sortNumber' => request('sortNumber', 0),
			'authority'  => request('authority', ''),
			'target'     => request('target', '_self'),
			'hide'       => request('hide', '0'),
		]);

        return $this->success('创建成功');
	}
}

