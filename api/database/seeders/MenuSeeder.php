<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Role;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        Menu::truncate();

		// 管理员菜单
		$authority = 'admin';

		$dashboard = Menu::create([
			'parentId' => 0,
			'title'     => '首页',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/dashboard',
			'component' => '/'.$authority.'/dashboard',
			'authority' => $authority,
		]);

		$profile = Menu::create([
			'parentId' => 0,
			'title'     => '用户中心',
			//'icon'      => 'el-icon-monitor',
			'path'      => '/user/profile',
			'component' => '/'.$authority.'/user/profile',
			'authority' => $authority,
			'hide' => 1,
		]);

		// 系统管理 
		$system = Menu::create([
			'parentId' => 0,
			'title'     => '系统管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/system',
			'component' => '',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId'   => $system->id,
			'title'      => '管理员管理',
			'icon'       => 'el-icon-monitor',
			'path'       => '/'.$authority.'/system/admin',
			'component'  => '/'.$authority.'/system/admin',
			'sortNumber' => '3',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId'   => $system->id,
			'title'      => '登录日志',
			'icon'       => 'el-icon-monitor',
			'path'       => '/'.$authority.'/system/login_log',
			'component'  => '/'.$authority.'/system/login-record',
			'sortNumber' => '5',
			'authority' => $authority,
		]);

        /*
		Menu::create([
			'parentId'   => $system->id,
			'title'      => '操作日志',
			'icon'       => 'el-icon-monitor',
			'path'       => '/'.$authority.'/system/op_log',
			'component'  => '/'.$authority.'/system/operation-record',
			'sortNumber' => '6',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId'   => $system->id,
			'title'      => '备份与恢复',
			'icon'       => 'el-icon-monitor',
			'path'       => '/'.$authority.'/system/backup',
			'component'  => '/'.$authority.'/system/backup',
			'sortNumber' => '5',
			'authority' => $authority,
		]);
         */

		// 用户管理 
		$trade = Menu::create([
			'parentId' => 0,
			'title'     => '订单流水',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/trades',
			'component' => '',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId' => $trade->id,
			'title'     => '订单管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/trade/trade',
			'component' => '/'.$authority.'/trade/trade',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId' => $trade->id,
			'title'     => '流水管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/trade/cashflow',
			'component' => '/'.$authority.'/trade/cashflow',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		// 商户管理 
		$merchant = Menu::create([
			'parentId' => 0,
			'title'     => '商户管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/merchant',
			'component' => '',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId'   => $merchant->id,
			'title'      => '商户管理',
			'icon'       => 'el-icon-monitor',
			'path'       => '/'.$authority.'/merchant/merchant',
			'component'  => '/'.$authority.'/merchant/merchant',
			'sortNumber' => '3',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId' => $merchant->id,
			'title'     => '提现管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/merchant/daifu_trade',
			'component' => '/'.$authority.'/merchant/daifu_trade',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId' => $merchant->id,
			'title'     => '代付管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/merchant/daifu',
			'component' => '/'.$authority.'/merchant/daifu',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId' => $merchant->id,
			'title'     => '数据统计',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/merchant/cashflow',
			'component' => '/'.$authority.'/merchant/cashflow',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId'   => $merchant->id,
			'title'      => '代理管理',
			'icon'       => 'el-icon-monitor',
			'path'       => '/'.$authority.'/merchant/agent',
			'component'  => '/'.$authority.'/merchant/agent',
			'sortNumber' => '3',
			'authority' => $authority,
		]);

		// 通道管理 
		$channel = Menu::create([
			'parentId' => 0,
			'title'     => '通道管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/channel',
			'component' => '',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId'  => $channel->id,
			'title'     => '支付码类型',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/channel/account_type',
			'component' => '/'.$authority.'/channel/account_type',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId'  => $channel->id,
			'title'     => '支付通道',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/channel/channel',
			'component' => '/'.$authority.'/channel/channel',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId'  => $channel->id,
			'title'     => '通道统计',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/channel/statistic',
			'component' => '/'.$authority.'/channel/statistic',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		// 支付码管理 
		$account_owner = Menu::create([
			'parentId' => 0,
			'title'     => '码商管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/account_owner',
			'component' => '',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId'   => $account_owner->id,
			'title'      => '码商管理',
			'icon'       => 'el-icon-monitor',
			'path'       => '/'.$authority.'/account_owner/account_owner',
			'component'  => '/'.$authority.'/account_owner/account_owner',
			'sortNumber' => '3',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId'  => $account_owner->id,
			'title'     => '支付码管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/account_owner/account',
			'component' => '/'.$authority.'/account_owner/account',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

        /*
		Menu::create([
			'parentId'   => $account_owner->id,
			'title'      => '押金充值',
			'icon'       => 'el-icon-monitor',
			'path'       => '/'.$authority.'/account_owner/recharge',
			'component'  => '/'.$authority.'/account_owner/recharge',
			'sortNumber' => '3',
			'authority' => $authority,
		]);
         */

		Menu::create([
			'parentId'  => $account_owner->id,
			'title'     => '码商统计',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/account_owner/statistic',
			'component' => '/'.$authority.'/account_owner/statistic',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId'  => $account_owner->id,
			'title'     => '支付码统计',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/account_owner/account_statistic',
			'component' => '/'.$authority.'/account_owner/account_statistic',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

        /*
		Menu::create([
			'parentId'   => $account_owner->id,
			'title'      => '提现',
			'icon'       => 'el-icon-monitor',
			'path'       => '/'.$authority.'/account_owner/withdraw',
			'component'  => '/'.$authority.'/account_owner/withdraw',
			'sortNumber' => '3',
			'authority' => $authority,
		]);
         */

        /*
		Menu::create([
			'parentId'   => $account_owner->id,
			'title'      => '码商流水',
			'icon'       => 'el-icon-monitor',
			'path'       => '/'.$authority.'/account_owner/cashflow',
			'component'  => '/'.$authority.'/account_owner/cashflow',
			'sortNumber' => '3',
			'authority' => $authority,
		]);
         */

		$role = Role::where('code', $authority)->first();

		$menu_ids = Menu::where('authority', $authority)->pluck('menuId');
		$role->menus()->sync($menu_ids);

		// 管理员菜单
		$authority = 'merchant';

        /*
		$dashboard = Menu::create([
			'parentId' => 0,
			'title'     => '首页',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/dashboard',
			'component' => '/'.$authority.'/dashboard',
			'authority' => $authority,
		]);
         */

		Menu::create([
			'parentId' => 0,
			'title'     => '用户中心',
			//'icon'      => 'el-icon-monitor',
			'path'      => '/user/profile',
			'component' => '/'.$authority.'/user/profile',
			'authority' => $authority,
			'hide' => 1,
		]);


		// 订单管理 
		 Menu::create([
			'parentId' => 0,
			'title'     => '订单管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/trade',
			'component' => '/'.$authority.'/trade',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId' => 0,
			'title'     => '提现管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/daifu_trade',
			'component' => '/'.$authority.'/daifu_trade',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId' => 0,
			'title'     => '代付管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/daifu',
			'component' => '/'.$authority.'/daifu',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId'   => 0,
			'title'      => '流水',
			'icon'       => 'el-icon-monitor',
			'path'       => '/'.$authority.'/cashflow',
			'component'  => '/'.$authority.'/cashflow',
			'sortNumber' => '3',
			'authority' => $authority,
		]);

		$role = Role::where('code', $authority)->first();

		$menu_ids = Menu::where('authority', $authority)->pluck('menuId');
		$role->menus()->sync($menu_ids);

		// 代理菜单
		$authority = 'agent';

		$dashboard = Menu::create([
			'parentId' => 0,
			'title'     => '首页',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/dashboard',
			'component' => '/'.$authority.'/dashboard',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId' => 0,
			'title'     => '用户中心',
			//'icon'      => 'el-icon-monitor',
			'path'      => '/user/profile',
			'component' => '/'.$authority.'/user/profile',
			'authority' => $authority,
			'hide' => 1,
		]);

		 Menu::create([
			'parentId' => 0,
			'title'     => '商户管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/merchant',
			'component' => '/'.$authority.'/merchant',
			'authority' => $authority,
		]);

		// 订单管理 
		 Menu::create([
			'parentId' => 0,
			'title'     => '订单管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/trade',
			'component' => '/'.$authority.'/trade',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId' => 0,
			'title'     => '代付管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/daifu_trade',
			'component' => '/'.$authority.'/daifu_trade',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId'   => 0,
			'title'      => '流水',
			'icon'       => 'el-icon-monitor',
			'path'       => '/'.$authority.'/cashflow',
			'component'  => '/'.$authority.'/cashflow',
			'sortNumber' => '3',
			'authority' => $authority,
		]);

		$role = Role::where('code', $authority)->first();

		$menu_ids = Menu::where('authority', $authority)->pluck('menuId');
		$role->menus()->sync($menu_ids);

		// 码商菜单
		$authority = 'account_owner';

        /*
		$dashboard = Menu::create([
			'parentId' => 0,
			'title'     => '首页',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/dashboard',
			'component' => '/'.$authority.'/dashboard',
			'authority' => $authority,
		]);
         */

		Menu::create([
			'parentId'  => 0,
			'title'     => '用户中心',
			'path'      => '/user/profile',
			'component' => '/'.$authority.'/user/profile',
			'authority' => $authority,
			'hide'      => 1,
		]);


		// 付款码管理 
		 Menu::create([
			'parentId' => 0,
			'title'     => '付款码管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/account',
			'component' => '/'.$authority.'/account',
			'authority' => $authority,
		]);

		// 订单管理 
		 Menu::create([
			'parentId' => 0,
			'title'     => '订单管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/trade',
			'component' => '/'.$authority.'/trade',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId' => 0,
			'title'     => '充值管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/recharge',
			'component' => '/'.$authority.'/recharge',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId' => 0,
			'title'     => '代付管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/daifu_trade',
			'component' => '/'.$authority.'/daifu_trade',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId' => 0,
			'title'     => '代付抢单',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/daifu_realtime',
			'component' => '/'.$authority.'/daifu_realtime',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

        /*
		Menu::create([
			'parentId' => 0,
			'title'     => '提现管理',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/withdraw',
			'component' => '/'.$authority.'/withdraw',
			'sortNumber' => '1',
			'authority' => $authority,
		]);
         */

		Menu::create([
			'parentId' => 0,
			'title'     => '团队成员',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/member',
			'component' => '/'.$authority.'/member',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId' => 0,
			'title'     => '团队订单',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/member_trade',
			'component' => '/'.$authority.'/member_trade',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId' => 0,
			'title'     => '团队数据统计',
			'icon'      => 'el-icon-monitor',
			'path'      => '/'.$authority.'/member_statistic',
			'component' => '/'.$authority.'/member_statistic',
			'sortNumber' => '1',
			'authority' => $authority,
		]);

		Menu::create([
			'parentId'   => 0,
			'title'      => '流水',
			'icon'       => 'el-icon-monitor',
			'path'       => '/'.$authority.'/cashflow',
			'component'  => '/'.$authority.'/cashflow',
			'sortNumber' => '3',
			'authority' => $authority,
		]);

		$role = Role::where('code', $authority)->first();

		$menu_ids = Menu::where('authority', $authority)->pluck('menuId');
		$role->menus()->sync($menu_ids);
    }
}
