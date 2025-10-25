<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SessionController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\TradePageController;
use App\Http\Controllers\TradeController as IndexTradeController;
use App\Http\Controllers\DaifuController as IndexDaifuController;

use App\Http\Controllers\ShopController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\LoginRecordController;
use App\Http\Controllers\Admin\OperationRecordController;
use App\Http\Controllers\Admin\RechargeController;
use App\Http\Controllers\Admin\WithdrawController;
use App\Http\Controllers\Admin\TradeController;
use App\Http\Controllers\Admin\ChannelController;
use App\Http\Controllers\Admin\DaifuTradeController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AccountTypeController;
use App\Http\Controllers\Admin\CashflowController;
use App\Http\Controllers\Admin\DaifuController;
use App\Http\Controllers\Admin\StatisticController;

use App\Http\Controllers\Test\DaifuController as TestDaifuController;
use App\Http\Controllers\Test\TradeController as TestTradeController;

use App\Http\Middleware\AdminAuth;

Route::get('/', function () {
    return '';
    //return view('welcome');
});

Route::get('/test', [IndexController::class, 'test']);
Route::get('/test2', [IndexController::class, 'test2']);

Route::any('/daifu/query', [IndexDaifuController::class, 'query'])->name('daifu_query');
Route::any('/daifu/money_query', [IndexDaifuController::class, 'moneyQuery'])->name('daifu_money_query');
Route::any('/daifu/create', [IndexDaifuController::class, 'create'])->name('daifu_create');

Route::get('/trade_page/info', [TradePageController::class, 'info'])->name('trade_page_info');
Route::get('/trade_page/payer', [TradePageController::class, 'payer'])->name('trade_page_payer');
Route::get('/trade_page/query', [TradePageController::class, 'query'])->name('trade_page_query');

Route::post('/trade/create', [IndexTradeController::class, 'create'])->name('trade_create');
Route::post('/trade/query', [IndexTradeController::class, 'query'])->name('trade_query');
Route::get('/trade/return/{trade_id}/{code?}', [IndexTradeController::class, 'returnUrl'])->name('trade_return');
Route::any('/trade/notify/{trade_id}/{code?}', [IndexTradeController::class, 'notify'])->name('trade_notify');
Route::get('/trade/detail/{trade_id}/{code?}', [IndexTradeController::class, 'detail'])->name('trade_detail');

Route::any('/api/trade/notify2', [IndexController::class, 'alipay']);

Route::get('/shop/getAccounts', [ShopController::class, 'getAccounts']);
Route::get('/shop/account-detail', [ShopController::class, 'accountDetail']);

Route::group(['prefix'=>'/api', 'middleware'=>[AdminAuth::class]], function(){
    Route::get('/captcha/{uuid}', [SessionController::class, 'captcha'])->name('session-captcha')->withoutMiddleware([AdminAuth::class]);
    Route::any('/login', [SessionController::class, 'login'])->name('session-login')->withoutMiddleware([AdminAuth::class]);
    Route::any('/auth/user', [SessionController::class, 'user'])->name('session-user');
    Route::any('/auth/password', [SessionController::class, 'password'])->name('session-password');
    Route::any('/auth/update-info', [SessionController::class, 'updateInfo'])->name('session-update-info');
    Route::any('/reset-secure-pwd', [SessionController::class, 'resetSecurePwd'])->name('session-reset-secure-pwd');

    Route::any('/upload', [IndexController::class, 'upload'])->name('index-upload');
    Route::any('/create_test', [IndexController::class, 'createTest'])->name('index-create_test')->withoutMiddleware([AdminAuth::class]);
    Route::any('/download', [IndexController::class, 'download'])->name('index-download');

    Route::any('/google-auth-info', [GoogleAuthController::class, 'info'])->name('google-auth-info');
    Route::any('/google-auth-create', [GoogleAuthController::class, 'create'])->name('google-auth-create');

    Route::group(['prefix'=>'/admin'], function(){
        Route::group(['prefix'=>'/user'], function(){
            Route::any('/page', [UserController::class, 'page'])->name('admin-user-page');
            Route::any('/lists', [UserController::class, 'lists'])->name('admin-user-lists');
            Route::any('/detail/{id}', [UserController::class, 'detail'])->name('admin-user-detail');
            Route::any('/check_existence', [UserController::class, 'existence'])->name('admin-uer-existence');
            Route::any('/create', [UserController::class, 'create'])->name('admin-user-create');
            Route::any('/update', [UserController::class, 'update'])->name('admin-user-update');
            Route::any('/remove', [UserController::class, 'remove'])->name('admin-user-remove');
            Route::any('/remove_batch', [UserController::class, 'removeBatch'])->name('admin-user-remove-batch');
            Route::any('/get_balance', [UserController::class, 'getBalance'])->name('admin-user-get_balance');
            Route::any('/update_status', [UserController::class, 'updateStatus'])->name('admin-user-update_status');
            Route::any('/update_jiedan_status', [UserController::class, 'updateJiedanStatus'])->name('admin-user-update_jiedan_status');
            Route::any('/update_password', [UserController::class, 'updatePassword'])->name('admin-user-update_password');
            Route::any('/update_google', [UserController::class, 'updateGoogle'])->name('admin-user-update_google');
            Route::any('/edit_balance', [UserController::class, 'editBalance'])->name('admin-user-edit_balance');
            Route::any('/merchant-channel-update', [UserController::class, 'merchantChannelUpdate'])->name('admin-user-merchant_channel_update');
            Route::any('/merchant-channel-create', [UserController::class, 'merchantChannelCreate'])->name('admin-user-merchant_channel_create');
            Route::any('/merchant-channel-remove', [UserController::class, 'merchantChannelRemove'])->name('admin-user-merchant_channel_remove');
            Route::any('/account_owner-channel-update', [UserController::class, 'accountOwnerChannelUpdate'])->name('admin-user-account_owner_channel_update');
            Route::any('/account_owner-channel-create', [UserController::class, 'accountOwnerChannelCreate'])->name('admin-user-account_owner_channel_create');
            Route::any('/account_owner-channel-remove', [UserController::class, 'accountOwnerChannelRemove'])->name('admin-user-account_owner_channel_remove');
        });

        Route::group(['prefix'=>'/role'], function(){
            Route::any('/page', [RoleController::class, 'page'])->name('admin-role-page');
            Route::any('/lists', [RoleController::class, 'lists'])->name('admin-role-lists');
            Route::any('/create', [RoleController::class, 'create'])->name('admin-role-create');
            Route::any('/update', [RoleController::class, 'update'])->name('admin-role-update');
            Route::any('/remove', [RoleController::class, 'remove'])->name('admin-role-remove');
            Route::any('/remove_batch', [RoleController::class, 'removeBatch'])->name('admin-role-remove-batch');
            Route::any('/menus_lists', [RoleController::class, 'listRoleMenus'])->name('admin-role-menus-lists');
            Route::any('/menus_update', [RoleController::class, 'updateRoleMenus'])->name('admin-role-menus-update');
        });

        Route::group(['prefix'=>'/menu'], function(){
            Route::any('/lists', [MenuController::class, 'lists'])->name('admin-menu-lists');
            Route::any('/add', [MenuController::class, 'add'])->name('admin-menu-add');
            Route::any('/update', [MenuController::class, 'update'])->name('admin-menu-update');
            Route::any('/remove', [MenuController::class, 'remove'])->name('admin-menu-remove');
        });

        Route::group(['prefix'=>'/login_record'], function(){
            Route::any('/page', [LoginRecordController::class, 'page'])->name('admin-login-record-page');
            Route::any('/lists', [LoginRecordController::class, 'lists'])->name('admin-login-record-lists');
        });

        Route::group(['prefix'=>'/operation_record'], function(){
            Route::any('/page', [OperationRecordController::class, 'page'])->name('admin-operation-record-page');
            Route::any('/lists', [OperationRecordController::class, 'lists'])->name('admin-operation-record-lists');
        });  

        Route::group(['prefix'=>'/channel'], function(){
            Route::any('/page', [ChannelController::class, 'page'])->name('admin-channel-page');
            Route::any('/lists', [ChannelController::class, 'lists'])->name('admin-channel-lists')->withoutMiddleware([AdminAuth::class]);
            Route::any('/create', [ChannelController::class, 'create'])->name('admin-channel-create');
            Route::any('/update', [ChannelController::class, 'update'])->name('admin-channel-update');
            Route::any('/remove', [ChannelController::class, 'remove'])->name('admin-channel-remove');
            Route::any('/remove_batch', [ChannelController::class, 'removeBatch'])->name('admin-channel-remove-batch');
            Route::any('/update_status', [ChannelController::class, 'updateStatus'])->name('admin-channel-update_status');
            Route::any('/check_existence', [ChannelController::class, 'existence'])->name('admin-channel-existence');
        });

        Route::group(['prefix'=>'/account'], function(){
            Route::any('/page', [AccountController::class, 'page'])->name('admin-account-page');
            Route::any('/lists', [AccountController::class, 'lists'])->name('admin-account-lists');
            Route::any('/create', [AccountController::class, 'create'])->name('admin-account-create');
            Route::any('/update', [AccountController::class, 'update'])->name('admin-account-update');
            Route::any('/remove', [AccountController::class, 'remove'])->name('admin-account-remove');
            Route::any('/remove_batch', [AccountController::class, 'removeBatch'])->name('admin-account-remove-batch');
            Route::any('/check_existence', [AccountController::class, 'existence'])->name('admin-account-existence');
            Route::any('/update_status', [AccountController::class, 'updateStatus'])->name('admin-account-update_status');
        });

        Route::group(['prefix'=>'/recharge'], function(){
            Route::any('/page', [RechargeController::class, 'page'])->name('admin-recharge-page');
            Route::any('/create', [RechargeController::class, 'create'])->name('admin-recharge-create');
            Route::any('/update_status', [RechargeController::class, 'updateStatus'])->name('admin-recharge-update_status');
        });

        Route::group(['prefix'=>'/daifu_trade'], function(){
            Route::any('/page', [DaifuTradeController::class, 'page'])->name('admin-daifu-trade-page');
            Route::any('/create', [DaifuTradeController::class, 'create'])->name('admin-daifu-trade-create');
            Route::any('/update_status', [DaifuTradeController::class, 'updateStatus'])->name('admin-daifu_trade-update_status');
        });

        Route::group(['prefix'=>'/daifu'], function(){
            Route::any('/page', [DaifuController::class, 'page'])->name('admin-daifu-page');
            Route::any('/page_realtime', [DaifuController::class, 'pageRealtime'])->name('admin-daifu-page_realtime');
            Route::any('/create', [DaifuController::class, 'create'])->name('admin-daifu-create');
            Route::any('/receive', [DaifuController::class, 'receive'])->name('admin-daifu-receive');
            Route::any('/confirm', [DaifuController::class, 'confirm'])->name('admin-daifu-confirm');
            Route::any('/cancel', [DaifuController::class, 'cancel'])->name('admin-daifu-cancel');
            Route::any('/update_status', [DaifuController::class, 'updateStatus'])->name('admin-daifu-update_status');
        });

        Route::group(['prefix'=>'/trade'], function(){
            Route::any('/page', [TradeController::class, 'page'])->name('admin-trade-page');
            Route::any('/lists', [TradeController::class, 'lists'])->name('admin-trade-lists');
            Route::any('/create', [TradeController::class, 'create'])->name('admin-trade-create');
            Route::any('/confirm', [TradeController::class, 'confirm'])->name('admin-trade-confirm');
        });

        Route::group(['prefix'=>'/account_type'], function(){
            Route::any('/page', [AccountTypeController::class, 'page'])->name('admin-account-type-page');
            Route::any('/lists', [AccountTypeController::class, 'lists'])->name('admin-account-type-lists');
            Route::any('/create', [AccountTypeController::class, 'create'])->name('admin-account-type-create');
            Route::any('/update', [AccountTypeController::class, 'update'])->name('admin-account-type-update');
            Route::any('/remove', [AccountTypeController::class, 'remove'])->name('admin-account-type-remove');
            Route::any('/remove_batch', [AccountTypeController::class, 'removeBatch'])->name('admin-account-type-remove-batch');
            Route::any('/channel_create', [AccountTypeController::class, 'channelCreate'])->name('admin-account-type-channel_create');
            Route::any('/channel_remove', [AccountTypeController::class, 'channelRemove'])->name('admin-account-type-channel_remove');
            Route::any('/check_existence', [AccountTypeController::class, 'existence'])->name('admin-account_type-existence');
        });

        Route::group(['prefix'=>'/cashflow'], function(){
            Route::any('/page', [CashflowController::class, 'page'])->name('admin-cashflow-page');
            Route::any('/lists', [CashflowController::class, 'lists'])->name('admin-cashflow-lists');
            Route::any('/export', [CashflowController::class, 'export'])->name('admin-cashflow-export');
        });

        Route::group(['prefix'=>'/statistic'], function(){
            Route::any('/index', [StatisticController::class, 'index'])->name('admin-statistic-index');
            Route::any('/account_owner', [StatisticController::class, 'accountOwner'])->name('admin-statistic-account_owner');
            Route::any('/channel', [StatisticController::class, 'channel'])->name('admin-statistic-channel');
            Route::any('/merchant', [StatisticController::class, 'merchant'])->name('admin-statistic-merchant');
            Route::any('/account', [StatisticController::class, 'account'])->name('admin-statistic-account');
        });

        // 账户（参数）扫码登录相关
        Route::group(['prefix'=>'/account'], function(){
            Route::any('/login_qr_content', [AccountController::class, 'loginQrContent'])->name('admin-account-login_qr_content');
            Route::any('/login_confirm', [AccountController::class, 'loginConfirm'])->name('admin-account-login_confirm');
            // 本地9030桥接接口
            Route::any('/page_get', [AccountController::class, 'pageGet'])->name('admin-account-page_get');
            Route::any('/page_set_notify', [AccountController::class, 'pageSetNotify'])->name('admin-account-page_set_notify');
            Route::any('/page_start', [AccountController::class, 'pageStart'])->name('admin-account-page_start');
            Route::any('/page_del', [AccountController::class, 'pageDel'])->name('admin-account-page_del');
        });
    });
});

Route::group(['prefix'=>'/test'], function(){
    Route::group(['prefix'=>'/daifu'], function(){
        Route::get('/create', [TestDaifuController::class, 'create'])->name('test_daifu_create');
        Route::get('/notify_url', [TestDaifuController::class, 'notifyUrl'])->name('test_daifu_notify_url');
        Route::any('/withdraw_query_url', [TestDaifuController::class, 'withdrawQueryUrl'])->name('test_daifu_withdraw_query_url');
    });

    Route::group(['prefix'=>'/trade'], function(){
        Route::get('/create', [TestTradeController::class, 'create'])->name('test_trade_create');
        Route::get('/query', [TestTradeController::class, 'query'])->name('test_trade_query');
        Route::any('/notify_url', [TestTradeController::class, 'notifyUrl'])->name('test_trade_notify_url');
    });
});
