<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script>
  import { updateDocumentTitle } from '@/utils/document-title-util';

  export default {
    name: 'App',
    created() {
      // 恢复主题
      this.$store.dispatch('theme/recoverTheme');
    },
    methods: {
      /* 路由切换更新浏览器标题 */
      setDocumentTitle() {
        updateDocumentTitle(
          this.$route,
          (key) => this.$t(key),
          this.$store.state.theme.tabs
        );
      }
    },
    watch: {
      '$i18n.locale'() {
        this.setDocumentTitle();
      },
      $route() {
        this.setDocumentTitle();
      }
    }
  };
</script>

<style>

  .ele-admin-header .ele-admin-logo{
      width: 200px !important;
  }

  .ele-admin-sidebar{
      width: 200px !important;
  }

</style>
