<template>
  <div class="ele-body" style="max-width:520px;margin:40px auto;">
    <el-card>
      <div slot="header">绑定谷歌验证码</div>
      <div>
        <div style="display:flex;align-items:center;">
          <div style="width:150px;height:150px;border:1px solid #eee;display:flex;align-items:center;justify-content:center;">
            <img v-if="qrcodeInline" :src="qrcodeInline" style="width:150px;height:150px;" />
            <span v-else>加载中...</span>
          </div>
          <div style="margin-left:12px;">
            <div style="font-size:12px;color:#999;">密钥</div>
            <div style="font-weight:600;word-break:break-all;">{{ googleCode || '-' }}</div>
          </div>
        </div>
        <el-form :model="form" :rules="rules" ref="form" label-width="80px" style="margin-top:16px;">
          <el-form-item label="验证码" prop="verify_code">
            <el-input v-model="form.verify_code" maxlength="6" placeholder="请输入6位验证码" />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" :loading="loading" @click="onSubmit">绑定</el-button>
            <el-button @click="fetchInfo" :loading="loadingInfo">刷新二维码</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
  </div>
</template>
<script>
import { googleAuthInfo, googleAuthCreate } from '@/api';
import router from '@/router';
import { getMenuRoutes } from '@/router/routes';
import { LAYOUT_PATH } from '@/config/setting';

export default {
  name: 'BindGoogle',
  data() {
    return {
      loading: false,
      loadingInfo: false,
      googleCode: '',
      qrcodeUrl: '',
      qrcodeInline: '',
      form: { verify_code: '' },
      rules: { verify_code: [{ required: true, message: '请输入6位验证码', trigger: 'blur' }] }
    };
  },
  computed: {},
  created() {
    const info = this.$store.state.user.info;
    if (info && info.google_seted === 1) {
      router.replace(LAYOUT_PATH);
      return;
    }
    this.fetchInfo();
  },
  methods: {
    async fetchInfo() {
      this.loadingInfo = true;
      try {
        const data = await googleAuthInfo();
        this.googleCode = data.google_code;
        this.qrcodeUrl = data.google_code_url;
        this.qrcodeInline = data.google_code_qr;
      } catch (e) {
        this.$message.error(e.message || '加载失败');
      } finally {
        this.loadingInfo = false;
      }
    },
    onSubmit() {
      this.$refs.form.validate(async (valid) => {
        if (!valid) return;
        this.loading = true;
        try {
          await googleAuthCreate({ google_code: this.googleCode, verify_code: this.form.verify_code });
          this.$message.success('绑定成功');
          // 重新获取用户信息，并补充动态路由后再跳转首页
          const { menus, homePath } = await this.$store.dispatch('user/fetchUserInfo');
          if (menus) {
            this.$router.addRoute(getMenuRoutes(menus, homePath));
          }
          router.replace(LAYOUT_PATH);
        } catch (e) {
          this.$message.error(e.message || '绑定失败');
        } finally {
          this.loading = false;
        }
      });
    }
  }
};
</script>
