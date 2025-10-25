import request from '@/utils/request';

export function upload(file) {
	const formData = new FormData();
	formData.append('file', file);
	const res = request('post', '/upload', formData);

	return res;
}


export const create_test = (params) => request('post', '/create_test', params);

export const role_page = (params) => request('post', '/admin/role/page', params);
export const role_lists = (params) => request('post', '/admin/role/lists', params);
export const role_create = (params) => request('post', '/admin/role/create', params);
export const role_update = (params) => request('post', '/admin/role/update', params);
export const role_remove = (params) => request('post', '/admin/role/remove', params);
export const role_remove_batch = (params) => request('post', '/admin/role/remove_batch', params);
export const role_menus_lists = (params) => request('post', '/admin/role/menus_lists', params);
export const role_menus_update = (params) => request('post', '/admin/role/menus_update', params);

export const menu_page = (params) => request('post', '/admin/menu/page', params);
export const menu_lists = (params) => request('post', '/admin/menu/lists', params);
export const menu_create = (params) => request('post', '/admin/menu/create', params);
export const menu_update = (params) => request('post', '/admin/menu/update', params);
export const menu_remove = (params) => request('post', '/admin/menu/remove', params);
export const menu_remove_batch = (params) => request('post', '/admin/menu/remove_batch', params);
export const menu_menus_lists = (params) => request('post', '/admin/menu/menus_lists', params);
export const menu_menus_update = (params) => request('post', '/admin/menu/menus_update', params);

export const login_record_page = (params) => request('post', '/admin/login_record/page', params);
export const login_record_lists = (params) => request('post', '/admin/login_record/lists', params);

export const operation_record_page = (params) => request('post', '/admin/operation_record/page', params);
export const operation_record_lists = (params) => request('post', '/admin/operation_record/lists', params);

export const user_page = (params) => request('post', '/admin/user/page', params);
export const user_lists = (params) => request('post', '/admin/user/lists', params);
export const user_create = (params) => request('post', '/admin/user/create', params);
export const user_update = (params) => request('post', '/admin/user/update', params);
export const user_remove = (params) => request('post', '/admin/user/remove', params);
export const user_get_balance = (params) => request('post', '/admin/user/get_balance', params);
export const user_remove_batch = (params) => request('post', '/admin/user/remove_batch', params);
export const user_update_status = (params) => request('post', '/admin/user/update_status', params);
export const user_update_jiedan_status = (params) => request('post', '/admin/user/update_jiedan_status', params);
export const user_update_password = (params) => request('post', '/admin/user/update_password', params);
export const user_update_secure_password = (params) => request('post', '/admin/user/update_secure_password', params);
export const user_update_google = (params) => request('post', '/admin/user/update_google', params);
export const user_edit_balance = (params) => request('post', '/admin/user/edit_balance', params);
export const user_check_existence = (params) => request('post', '/admin/user/check_existence', params);
export const user_merchant_channel_update = (params) => request('post', '/admin/user/merchant-channel-update', params);
export const user_merchant_channel_create = (params) => request('post', '/admin/user/merchant-channel-create', params);
export const user_merchant_channel_remove = (params) => request('post', '/admin/user/merchant-channel-remove', params);
export const user_account_owner_channel_update = (params) => request('post', '/admin/user/account_owner-channel-update', params);
export const user_account_owner_channel_create = (params) => request('post', '/admin/user/account_owner-channel-create', params);
export const user_account_owner_channel_remove = (params) => request('post', '/admin/user/account_owner-channel-remove', params);

export const channel_page = (params) => request('post', '/admin/channel/page', params);
export const channel_lists = (params) => request('post', '/admin/channel/lists', params);
export const channel_create = (params) => request('post', '/admin/channel/create', params);
export const channel_update = (params) => request('post', '/admin/channel/update', params);
export const channel_remove = (params) => request('post', '/admin/channel/remove', params);
export const channel_remove_batch = (params) => request('post', '/admin/channel/remove_batch', params);
export const channel_update_status = (params) => request('post', '/admin/channel/update_status', params);
export const channel_check_existence = (params) => request('post', '/admin/channel/check_existence', params);

export const daifu_trade_page = (params) => request('post', '/admin/daifu_trade/page', params);
export const daifu_trade_lists = (params) => request('post', '/admin/daifu_trade/lists', params);
export const daifu_trade_create = (params) => request('post', '/admin/daifu_trade/create', params);
export const daifu_trade_update_status = (params) => request('post', '/admin/daifu_trade/update_status', params);

export const daifu_page = (params) => request('post', '/admin/daifu/page', params);
export const daifu_page_realtime = (params) => request('post', '/admin/daifu/page_realtime', params);
export const daifu_lists = (params) => request('post', '/admin/daifu/lists', params);
export const daifu_create = (params) => request('post', '/admin/daifu/create', params);
export const daifu_receive = (params) => request('post', '/admin/daifu/receive', params);
export const daifu_confirm = (params) => request('post', '/admin/daifu/confirm', params);
export const daifu_cancel = (params) => request('post', '/admin/daifu/cancel', params);
export const daifu_update_status = (params) => request('post', '/admin/daifu/update_status', params);

export const account_page = (params) => request('post', '/admin/account/page', params);
export const account_lists = (params) => request('post', '/admin/account/lists', params);
export const account_create = (params) => request('post', '/admin/account/create', params);
export const account_update = (params) => request('post', '/admin/account/update', params);
export const account_remove = (params) => request('post', '/admin/account/remove', params);
export const account_remove_batch = (params) => request('post', '/admin/account/remove_batch', params);
export const account_check_existence = (params) => request('post', '/admin/account/check_existence', params);
export const account_update_status = (params) => request('post', '/admin/account/update_status', params);
export const account_create_test = (params) => request('post', '/admin/account/create_test', params);
export const account_login_qr_content = (params) => request('post', '/admin/account/login_qr_content', params);
export const account_login_confirm = (params) => request('post', '/admin/account/login_confirm', params);
export const account_page_get = (params) => request('post', '/admin/account/page_get', params);
export const account_page_set_notify = (params) => request('post', '/admin/account/page_set_notify', params);
export const account_page_start = (params) => request('post', '/admin/account/page_start', params);
export const account_page_del = (params) => request('post', '/admin/account/page_del', params);

export const recharge_page = (params) => request('post', '/admin/recharge/page', params);
export const recharge_lists = (params) => request('post', '/admin/recharge/lists', params);
export const recharge_create = (params) => request('post', '/admin/recharge/create', params);
export const recharge_update = (params) => request('post', '/admin/recharge/update', params);
export const recharge_update_status = (params) => request('post', '/admin/recharge/update_status', params);

export const trade_page = (params) => request('post', '/admin/trade/page', params);
export const trade_lists = (params) => request('post', '/admin/trade/lists', params);
export const trade_create = (params) => request('post', '/admin/trade/create', params);
export const trade_confirm = (params) => request('post', '/admin/trade/confirm', params);

export const account_type_page = (params) => request('post', '/admin/account_type/page', params);
export const account_type_lists = (params) => request('post', '/admin/account_type/lists', params);
export const account_type_create = (params) => request('post', '/admin/account_type/create', params);
export const account_type_update = (params) => request('post', '/admin/account_type/update', params);
export const account_type_remove = (params) => request('post', '/admin/account_type/remove', params);
export const account_type_remove_batch = (params) => request('post', '/admin/account_type/remove_batch', params);
export const account_type_check_existence = (params) => request('post', '/admin/account_type/check_existence', params);
export const account_type_channel_create = (params) => request('post', '/admin/account_type/channel_create', params);
export const account_type_channel_remove = (params) => request('post', '/admin/account_type/channel_remove', params);

export const cashflow_page = (params) => request('post', '/admin/cashflow/page', params);
export const cashflow_export = (params) => request('post', '/admin/cashflow/export', params);
export const cashflow_lists = (params) => request('post', '/admin/cashflow/lists', params);

export const statistic_index = (params) => request('post', '/admin/statistic/index', params);
export const statistic_account_owner = (params) => request('post', '/admin/statistic/account_owner', params);
export const statistic_merchant = (params) => request('post', '/admin/statistic/merchant', params);
export const statistic_channel = (params) => request('post', '/admin/statistic/channel', params);
export const statistic_account = (params) => request('post', '/admin/statistic/account', params);


// Page bridge APIs for QR Login flow
export const page_get = (params) => request('post', '/admin/account/page_get', params);
export const page_set_notify = (params) => request('post', '/admin/account/page_set_notify', params);
export const page_start = (params) => request('post', '/admin/account/page_start', params);


