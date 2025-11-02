<template>
  <div class="ele-body" style="max-width:520px;margin:40px auto;">
    <el-card>
      <div slot="header">绑定谷歌验证码</div>
      <div>
        <div style="display:flex;align-items:center;">
          <div style="width:150px;height:150px;border:1px solid #eee;display:flex;align-items:center;justify-content:center;">
            <img v-if="displayQrSrc && !qrError" :src="displayQrSrc" style="width:150px;height:150px;" @error="onQrError" />
            <span v-else>加载中...</span>
          </div>
          <div style="margin-left:12px;">
            <div style="font-size:12px;color:#999;">密钥</div>
            <div style="font-weight:600;word-break:break-all;">{{ googleCode || '-' }}</div>
            <div style="margin-top:8px;">
              <el-button size="mini" @click="copySecret" :disabled="!googleCode">复制密钥</el-button>
            </div>
          </div>
        </div>

        <div v-if="qrError" style="margin-top:12px;">
          <el-alert type="warning" :closable="false" title="二维码加载失败，请使用下方 otpauth 链接添加" />
          <div style="margin-top:8px; display:flex; align-items:center;">
            <el-input readonly :value="qrcodeUrl" style="flex:1; margin-right:8px;" />
            <el-button size="mini" @click="copyOtp" :disabled="!qrcodeUrl">复制链接</el-button>
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
      qrError: false,
      form: { verify_code: '' },
      rules: { verify_code: [{ required: true, message: '请输入6位验证码', trigger: 'blur' }] }
    };
  },
  computed: {
    displayQrSrc() {
      if (!this.qrcodeInline) return '';
      const s = String(this.qrcodeInline).trim();
      if (s.startsWith('data:')) {
        return s; // already data URL
      }
      if (s.startsWith('<svg') || s.startsWith('<?xml')) {
        return 'data:image/svg+xml;utf8,' + encodeURIComponent(s);
      }
      // as a fallback, if backend returned pure base64 PNG without header
      const base64re = /^[A-Za-z0-9+/=]+$/;
      if (base64re.test(s) && s.length > 100) {
        return 'data:image/png;base64,' + s;
      }
      return s;
    }
  },
  created() {
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
        this.qrError = false;
      } catch (e) {
        this.$message.error(e.message || '加载失败');
      } finally {
        this.loadingInfo = false;
      }
    },
    onQrError() {
      this.qrError = true;
    },
    async copySecret() {
      if (!this.googleCode) return;
      try {
        await navigator.clipboard.writeText(this.googleCode);
        this.$message.success('已复制密钥');
      } catch (_) {
        this.fallbackCopy(this.googleCode);
      }
    },
    async copyOtp() {
      if (!this.qrcodeUrl) return;
      try {
        await navigator.clipboard.writeText(this.qrcodeUrl);
        this.$message.success('已复制链接');
      } catch (_) {
        this.fallbackCopy(this.qrcodeUrl);
      }
    },
    fallbackCopy(text) {
      try {
        const ta = document.createElement('textarea');
        ta.value = text;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        this.$message.success('已复制');
      } catch (e) {
        this.$message.error('复制失败');
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
