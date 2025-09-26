<!-- 修改密码弹窗 -->
<template>
  <ele-modal width="420px" title="设置支付密码" :visible="visible" :append-to-body="true" :close-on-click-modal="true"
    @update:visible="updateVisible" @closed="onClose">
    <el-form ref="form" :model="form" :rules="rules" label-width="120px" @keyup.enter.native="save">
      <el-form-item label="原支付密码">
        <el-input show-password v-model="form.old_pwd" placeholder="请输入原支付密码" />
      </el-form-item>
      <el-form-item label="新支付密码:" prop="google_code">
        <el-input show-password v-model="form.new_pwd" placeholder="请输入新支付密码" />
      </el-form-item>
      <el-form-item label="验证码:" prop="verify_code">
        <el-input show-password v-model="form.new_pwd_confirm" placeholder="请确认新支付密码" />
      </el-form-item>
    </el-form>
    <template v-slot:footer>
      <el-button @click="updateVisible(false)">取消</el-button>
      <el-button type="primary" @click="save">绑定</el-button>
    </template>
  </ele-modal>
</template>

<script>
  import {
    resetSecurePwd,
    updateUserInfo
  } from '@/api';
  export default {
    props: {
      visible: Boolean
    },
    data() {
      return {
        // 按钮 loading
        loading: false,
        // 表单数据
        form: {
          old_pwd: '',
          new_pwd: '',
          new_pwd_confirm: ''
        },
        // 表单验证
        rules: {
          new_pwd: [{
            required: true,
            message: '请输入新支付密码',
            trigger: 'blur'
          }],
          new_pwd_confirm: [{
            required: true,
            message: '请确认新支付密码',
            trigger: 'blur'
          }],
        }
      };
    },
    created() {
    },
    methods: {
      /* 修改 visible */
      updateVisible(value) {
        this.$emit('update:visible', value);
      },
      /* 保存修改 */
      save() {
        this.$refs.form.validate((valid) => {
          if (valid) {
            this.loading = true;
            resetSecurePwd(this.form)
              .then(() => {
                this.loading = false;
                this.$message.success('设置成功');

                updateUserInfo(this.form)
                  .then(() => {
                    this.loading = false;
                    this.$message.success('操作成功');
                    this.updateLoginUser(this.form);
                  })
                  .catch((e) => {
                    this.loading = false;
                    this.$message.error(e.message);
                  });

                this.updateVisible(false);
              })
              .catch((e) => {
                this.loading = false;
                this.$message.error(e.message);
              });
          } else {
            return false;
          }
        });
      },
      /* 关闭回调 */
      onClose() {
        this.form = {
          old_pwd: '',
          new_pwd: '',
          new_pwd_confirm: ''
        };
        this.$refs.form.resetFields();
        this.loading = false;
      }
    }
  };
</script>
