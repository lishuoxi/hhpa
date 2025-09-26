<template>
  <div
    :class="[
      'login-wrapper',
      ['', 'login-form-right', 'login-form-left'][direction]
    ]"
  >
    <el-form
      ref="form"
      size="large"
      :model="form"
      :rules="rules"
      class="login-form ele-bg-white"
      @keyup.enter.native="submit"
    >
      <h4>{{ $t('login.title') }}</h4>
      <el-form-item prop="username">
        <el-input
          clearable
          v-model="form.username"
          prefix-icon="el-icon-user"
          :placeholder="$t('login.username')"
        />
      </el-form-item>
      <el-form-item prop="password">
        <el-input
          show-password
          v-model="form.password"
          prefix-icon="el-icon-lock"
          :placeholder="$t('login.password')"
        />
      </el-form-item>
      <el-form-item prop="google_code">
        <el-input
          show-password
          v-model="form.google_code"
          prefix-icon="el-icon-lock"
          placeholder="谷歌验证码,没绑定的不用填"
        />
      </el-form-item>
      <el-form-item prop="code">
        <div class="login-input-group">
          <el-input
            clearable
            v-model="form.code"
            prefix-icon="el-icon-_vercode"
            :placeholder="$t('login.code')"
          />
          <img
            alt=""
            v-if="captcha"
            :src="captcha"
            class="login-captcha"
            @click="changeCaptcha"
          />
        </div>
      </el-form-item>

      <div class="el-form-item">
        <el-button
          size="large"
          type="primary"
          class="login-btn"
          :loading="loading"
          @click="submit"
        >
          {{ loading ? $t('login.loading') : $t('login.login') }}
        </el-button>
      </div>

    </el-form>
    <div class="login-copyright">
      copyright © 2024  all rights reserved.
    </div>
    <!-- 多语言切换 -->
    <div style="position: absolute; right: 30px; top: 20px">
      <i18n-icon
        :icon-style="{ fontSize: '22px', color: '#fff', cursor: 'pointer' }"
      />
    </div>
  </div>
</template>

<script>
  import I18nIcon from '@/layout/components/i18n-icon.vue';
  import { getToken, setToken } from '@/utils/token-util';
  //import { login } from '@/api';
  import { doLogin } from '@/api/index';
  import { generateUUID } from '@/utils/uuid';

  export default {
    // eslint-disable-next-line vue/multi-word-component-names
    name: 'Login',
    components: { I18nIcon },
    data() {
      return {
        // 登录框方向, 0居中, 1居右, 2居左
        direction: 0,
        // 加载状态
        loading: false,
        // 表单数据
        form: {
          username: '',
          password: '',
          google_code: '',
          remember: true,
          code: '',
          key: generateUUID()
        },
        // 验证码base64数据
        //captcha: '',
        // 验证码内容, 实际项目去掉
        text: '',
        d: new Date().getTime()
      };
    },
    computed: {
      captcha() {
        return process.env.VUE_APP_API_BASE_URL + '/captcha/' + this.form.key + '?k=' + this.d;
      },
      // 表单验证规则
      rules() {
        return {
          username: [
            {
              required: true,
              message: this.$t('login.username'),
              type: 'string',
              trigger: 'blur'
            }
          ],
          password: [
            {
              required: true,
              message: this.$t('login.password'),
              type: 'string',
              trigger: 'blur'
            }
          ],
          google_code: [
            {

            }
          ]
        };
      }
    },
    created() {
      if (getToken()) {
        this.goHome();
      } else {
        this.changeCaptcha();
      }
    },
    methods: {
      /* 提交 */
      submit() {
        this.$refs.form.validate((valid) => {
          if (!valid) {
            return false;
          }

          this.loading = true;
          doLogin(this.form)
            .then((res) => {
              setToken(res.userinfo.token, this.form.remember);
              this.loading = false;
              this.$message.success('登陆成功');
              this.goHome();
            })
            .catch((e) => {
              console.log('错误');
              console.log(e);
              this.loading = false;
              this.changeCaptcha();
              this.$message.error(e.message);
            });
        });
      },
      /* 跳转到首页 */
      goHome() {
        this.$router.push(this.$route?.query?.from ?? '/').catch((e) => {
          console.log('登录后跳转页面错误');
          console.log(e);
        });
      },
      /* 更换图形验证码 */
      changeCaptcha() {
        this.d = new Date().getTime();
      }
    }
  };
</script>

<style lang="scss" scoped>
  /* 背景 */
  .login-wrapper {
    padding: 50px 20px;
    position: relative;
    box-sizing: border-box;
    background-image: url('@/assets/bg-login.jpg');
    background-repeat: no-repeat;
    background-size: cover;
    min-height: 100vh;

    &:before {
      content: '';
      background-color: rgba(0, 0, 0, 0.2);
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
    }
  }

  /* 卡片 */
  .login-form {
    margin: 0 auto;
    width: 360px;
    max-width: 100%;
    padding: 25px 30px;
    position: relative;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
    box-sizing: border-box;
    border-radius: 4px;
    z-index: 2;

    h4 {
      text-align: center;
      margin: 0 0 25px 0;
    }

    & > .el-form-item {
      margin-bottom: 25px;
    }
  }

  .login-form-right .login-form {
    margin: 0 15% 0 auto;
  }

  .login-form-left .login-form {
    margin: 0 auto 0 15%;
  }

  /* 验证码 */
  .login-input-group {
    display: flex;
    align-items: center;

    :deep(.el-input) {
      flex: 1;
    }
  }

  .login-captcha {
    height: 38px;
    width: 102px;
    margin-left: 10px;
    border-radius: 4px;
    border: 1px solid #dcdfe6;
    text-align: center;
    cursor: pointer;

    &:hover {
      opacity: 0.75;
    }
  }

  .login-btn {
    display: block;
    width: 100%;
  }

  /* 第三方登录图标 */
  .login-oauth-icon {
    color: #fff;
    padding: 5px;
    margin: 0 10px;
    font-size: 18px;
    border-radius: 50%;
    cursor: pointer;
  }

  /* 底部版权 */
  .login-copyright {
    color: #eee;
    padding-top: 20px;
    text-align: center;
    position: relative;
    z-index: 1;
  }

  /* 响应式 */
  @media screen and (min-height: 550px) {
    .login-form {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translateX(-50%);
      margin-top: -220px;
    }

    .login-form-right .login-form,
    .login-form-left .login-form {
      left: auto;
      right: 15%;
      transform: translateX(0);
      margin: -220px auto auto auto;
    }

    .login-form-left .login-form {
      right: auto;
      left: 15%;
    }

    .login-copyright {
      position: absolute;
      bottom: 20px;
      right: 0;
      left: 0;
    }
  }

  @media screen and (max-width: 768px) {
    .login-form-right .login-form,
    .login-form-left .login-form {
      left: 50%;
      right: auto;
      transform: translateX(-50%);
      margin-right: auto;
    }
  }
</style>
