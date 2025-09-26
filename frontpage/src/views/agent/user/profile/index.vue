<template>
  <div class="ele-body">
    <el-row :gutter="15">
      <el-col v-bind="styleResponsive ? { md: 6, sm: 8 } : { span: 6 }">
        <el-card shadow="never" body-style="padding: 25px;">
          <div class="user-info-card">
            <div class="user-info-avatar-group">
              <img class="user-info-avatar" src="/assets/avatar.jpg" alt="" />
              <i class="el-icon-upload2"></i>
            </div>
            <h2 class="user-info-name">{{ loginUser.username }}</h2>
            <div class="user-info-desc">{{ loginUser.username }}</div>
          </div>
          <!-- <div class="user-info-list">
            <div class="user-info-item">
              <i class="el-icon-user"></i>
              <span>当前用户: {{ loginUser.realname }}</span>
            </div>
            <div class="user-info-item">
              <i class="el-icon-user"></i>
              <span>登陆名: {{ loginUser.username }}</span>
            </div>
            <div class="user-info-item">
              <i class="el-icon-office-building"></i>
              <span>余额: {{ loginUser.balance }}</span>
            </div>
          </div>
          <div style="margin: 30px 0 20px 0">
            <el-divider class="ele-divider-dashed ele-divider-base" />
          </div>
          <h6 class="ele-text" style="margin-bottom: 8px">标签</h6>
          <div class="user-info-tags">
            <el-tag size="mini" type="info">很有想法的</el-tag>
            <el-tag size="mini" type="info">专注设计</el-tag>
            <el-tag size="mini" type="info">辣~</el-tag>
            <el-tag size="mini" type="info">大长腿</el-tag>
            <el-tag size="mini" type="info">川妹子</el-tag>
            <el-tag size="mini" type="info">海纳百川</el-tag>
          </div>-->
        </el-card>
      </el-col>
      <el-col v-bind="styleResponsive ? { md: 18, sm: 16 } : { span: 18 }">
        <el-card shadow="never" body-style="padding: 0;">
          <el-tabs v-model="active" class="user-info-tabs">
            <el-tab-pane label="基本信息" name="info">
              <el-form
                ref="form"
                :model="form"
                :rules="rules"
                label-width="90px"
                style="max-width: 450px; padding: 34px 20px 0 20px"
                @keyup.enter.native="save"
                @submit.native.prevent
              >
                <el-form-item label="登陆账号:" prop="username">
                  <el-input
                    disabled
                    v-model="form.username"
                    placeholder="请输入登陆账号"
                    clearable
                  />
                </el-form-item>
                <el-form-item label="真实名称:" prop="realname">
                  <el-input
                    v-model="form.realname"
                    placeholder="请输入真实名称"
                    clearable
                  />
                </el-form-item>
                <el-form-item label="后台登陆IP:">
                  <el-input
                    v-model="form.admin_secure_ips"
                    placeholder="请输入后台安全登陆IP,多个IP地址用逗号分割,为空则不限制"
                    :rows="4"
                    type="textarea"
                  />
                </el-form-item>
                <el-form-item>
                  <el-button type="primary" :loading="loading" @click="save">
                    保存更改
                  </el-button>
                </el-form-item>
              </el-form>
            </el-tab-pane>
            <el-tab-pane label="账号安全" name="account">
              <div class="user-account-list">
                <div class="user-account-item ele-cell">
                  <div class="ele-cell-content">
                    <div>支付密码</div>
                    <div class="ele-text-secondary" v-if="loginUser.secure_password_seted">
                      已设置
                    </div>
                    <div class="ele-text-secondary" v-if="!loginUser.secure_password_seted">
                      未设置
                    </div>
                  </div>
                  <el-link type="primary" :underline="false">去修改</el-link>
                </div>
                <el-divider />
                <div class="user-account-item ele-cell">
                  <div class="ele-cell-content">
                    <div>谷歌验证码</div>
                    <div class="ele-text-secondary" v-if="loginUser.google_seted">
                      已绑定
                    </div>
                    <div class="ele-text-secondary" v-if="!loginUser.google_seted">
                      未绑定
                    </div>
                  </div>
                  <el-link type="primary" :underline="false" @click="googleAuthVisible = true;" v-if="loginUser.google_seted">
                    修改绑定</el-link>
                  <el-link type="primary" :underline="false" @click="googleAuthVisible = true;" v-if="!loginUser.google_seted">
                    去绑定</el-link>
                </div>
                <el-divider />
              </div>
            </el-tab-pane>
          </el-tabs>
        </el-card>
      </el-col>
    </el-row>

    <!-- 修改密码弹窗 -->
    <google-auth-modal :visible.sync="googleAuthVisible" />
  </div>

</template>

<script>
  import GoogleAuthModal from './google_auth_modal.vue';
  import * as api from '@/api/index';

  export default {
    name: 'UserProfile',
    components: { GoogleAuthModal },
    data() {
      return {
        // tab页选中
        active: 'info',
        // 表单数据
        form: {
          username: '',
          realname: '1',
          admin_secure_ips: '',
        },
        // 表单验证规则
        rules: {
          username: [
            {
              required: true,
              message: '请输入昵称',
              trigger: 'blur'
            }
          ],
          realname: [
            {
              required: true,
              message: '请输入真实名称',
              trigger: 'blur'
            }
          ],

        },
        // 保存按钮loading
        loading: false,
        // 是否显示裁剪弹窗
        visible: false,
        googleAuthVisible: false,
      };
    },
    computed: {
      // 登录用户信息
      loginUser() {
        return this.$store.state.user.info;
      },
      // 是否开启响应式布局
      styleResponsive() {
        return this.$store.state.theme.styleResponsive;
      }
    },
    created() {
      Object.assign(this.form, this.loginUser, {

      });

      console.log('this.form');
      console.log(this.form);
    },
    methods: {
      /* 保存更改 */
      save() {
        this.$refs.form.validate((valid) => {
          if (!valid) {
            return false;
          }
          this.loading = true;

          api.updateUserInfo(this.form)
            .then(() => {
              this.loading = false;
              this.$message.success('操作成功');
              this.updateLoginUser(this.form);
            })
            .catch((e) => {
              this.loading = false;
              this.$message.error(e.message);
            });
        });
      },
      /* 修改登录用户 */
      updateLoginUser(data) {
        this.$store.dispatch('user/setInfo', { ...this.loginUser, ...data });
      },

    }
  };
</script>

<style lang="scss" scoped>
  .ele-body {
    padding-bottom: 0;
  }

  .el-card {
    margin-bottom: 15px;
  }

  /* 用户资料卡片 */
  .user-info-card {
    padding: 8px 0;
    text-align: center;

    .user-info-avatar-group {
      position: relative;
      cursor: pointer;
      margin: 0 auto;
      width: 110px;
      height: 110px;
      border-radius: 50%;
      overflow: hidden;

      & > i {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #fff;
        font-size: 30px;
        display: none;
        z-index: 2;
      }

      &:hover {
        & > i {
          display: block;
        }

        &:after {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(0, 0, 0, 0.3);
        }
      }
    }

    .user-info-avatar {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      object-fit: cover;
    }

    .user-info-name {
      font-size: 24px;
      margin-top: 20px;
    }

    .user-info-desc {
      margin-top: 8px;
    }
  }

  /* 用户信息列表 */
  .user-info-list {
    margin-top: 30px;

    .user-info-item {
      margin-bottom: 16px;
      display: flex;
      align-items: baseline;

      & > i {
        margin-right: 10px;
        font-size: 16px;
      }

      & > span {
        flex: 1;
        display: block;
      }
    }
  }

  /* 用户标签 */
  .user-info-tags .el-tag {
    margin: 10px 10px 0 0;
  }

  /* 用户账号绑定列表 */
  .user-account-list {
    padding: 16px 20px;

    .user-account-item {
      padding: 15px;

      .ele-text-secondary {
        margin-top: 6px;
      }

      .user-account-icon {
        width: 42px;
        height: 42px;
        line-height: 42px;
        text-align: center;
        color: #fff;
        font-size: 26px;
        border-radius: 50%;
        background-color: #3492ed;
        box-sizing: border-box;

        &.el-icon-_wechat {
          background-color: #4daf29;
          font-size: 28px;
        }

        &.el-icon-_alipay {
          background-color: #1476fe;
          padding-left: 5px;
          font-size: 32px;
        }
      }
    }
  }

  /* tab 页签 */
  .user-info-tabs {
    :deep(.el-tabs__nav-wrap) {
      padding-left: 24px;
    }

    :deep(.el-tabs__item) {
      height: 50px;
      line-height: 50px;
      font-size: 15px;
    }
  }
</style>
