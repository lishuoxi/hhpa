/**
 * axios 实例
 */
import axios from 'axios';
import router from '@/router';
import { MessageBox } from 'element-ui';
import { API_BASE_URL, TOKEN_HEADER_NAME, LAYOUT_PATH } from '@/config/setting';
import { getToken, setToken } from './token-util';
import { logout } from './page-tab-util';

const service = axios.create({
  baseURL: API_BASE_URL
});

/**
 * 添加请求拦截器
 */
service.interceptors.request.use(
  (config) => {
    // 添加 token 到 header
    const token = getToken();
    if (token && config.headers) {
      config.headers.common[TOKEN_HEADER_NAME] = token;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

/**
 * 添加响应拦截器
 */
service.interceptors.response.use(
  (res) => {
    //console.log('网络请求返回: ');
    //console.log(res);
    // 登录过期处理
    if (res.data?.code === 401) {
      const currentPath = router.currentRoute.path;
      if (currentPath === LAYOUT_PATH) {
        logout(true);
      } else {
        MessageBox.alert('登录状态已过期, 请退出重新登录!', '系统提示', {
          confirmButtonText: '重新登录',
          callback: (action) => {
            if (action === 'confirm') {
              logout(false, currentPath);
            }
          },
          beforeClose: () => {
            MessageBox.close();
          }
        });
      }
      return Promise.reject(new Error(res.data.message));
    }
    // token 自动续期
    const token = res.headers[TOKEN_HEADER_NAME.toLowerCase()];
    if (token) {
      setToken(token);
    }
    return res;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export async function request(method, url, params, config) {

  const res = await service({method:method, url:url, data:params}, config);

  if(res.status != 200){
    return Promise.reject(new Error('网络返回' + res.statusText));
  }

  var resData = res.data;

  if (resData.code === 0) {
    return resData.data;
  }
  return Promise.reject(new Error(resData.message));
}


//export default request;


export default request;
