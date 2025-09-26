import request from '@/utils/request';

export const updateUserInfo = (params) => request('post', '/auth/update-info', params);
export const getUserInfo = (params) => request('post', '/auth/user', params);
export const updatePassword = (params) => request('post', '/auth/password', params);
export const doLogin = (params) => request('post', '/login', params);
export const googleAuthInfo = (params) => request('post', '/google-auth-info', params);
export const googleAuthCreate = (params) => request('post', '/google-auth-create', params);
export const resetSecurePwd = (params) => request('post', '/reset-secure-pwd', params);
