import Vue from 'vue';
import App from './App.vue';
import store from './store';
import router from './router';
import permission from './utils/permission';
import { MAP_KEY, LICENSE_CODE } from '@/config/setting';
import EleAdmin from 'ele-admin';
import VueClipboard from 'vue-clipboard2';
import i18n from './i18n';
import './styles/index.scss';

import UserSelect from './components/UserSelect';
import ChannelSelect from './components/ChannelSelect';
import AccountTypeSelect from './components/AccountTypeSelect';
import AccountSelect from './components/AccountSelect';

Vue.config.productionTip = false;

Vue.use(EleAdmin, {
  response: {
    dataName: 'list'
  },
  mapKey: MAP_KEY,
  license: LICENSE_CODE,
  i18n: (key, value) => i18n.t(key, value)
});

Vue.prototype.$ELEMENT = { size: 'mini', zIndex: 3000 };

Vue.use(permission);
Vue.use(VueClipboard);

Vue.component('UserSelect', UserSelect);
Vue.component('ChannelSelect', ChannelSelect);
Vue.component('AccountTypeSelect', AccountTypeSelect);
Vue.component('AccountSelect', AccountSelect);

new Vue({
  router,
  store,
  i18n,
  render: (h) => h(App)
}).$mount('#app');
