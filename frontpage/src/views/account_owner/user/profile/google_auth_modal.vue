<!-- 修改密码弹窗 -->
<template>
  <ele-modal width="420px" title="绑定谷歌" :visible="visible" :append-to-body="true" :close-on-click-modal="true"
    @update:visible="updateVisible" @closed="onClose">
    <el-form ref="form" :model="form" :rules="rules" label-width="82px" @keyup.enter.native="save">
      <el-form-item label="绑定谷歌">
        <ele-qr-code-svg :value="form.google_code_url" :size="120" />
      </el-form-item>
      <el-form-item label="谷歌码:" prop="google_code">
        <el-input disabled v-model="form.google_code" />
      </el-form-item>
      <el-form-item label="验证码:" prop="verify_code">
        <el-input show-password v-model="form.verify_code" placeholder="请输入验证码" />
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
    googleAuthInfo,
    googleAuthCreate
  } from '@/api';
  import EleQrCodeSvg from 'ele-admin/es/ele-qr-code-svg';
  import { logout } from '@/utils/page-tab-util';
  export default {
    props: {
      visible: Boolean
    },
    components: { EleQrCodeSvg },
    data() {
      return {
        // 按钮 loading
        loading: false,
        // 表单数据
        form: {
          google_code: '',
          google_code_url: '',
          verify_code: ''
        },
        // 表单验证
        rules: {
          verify_code: [{
            required: true,
            message: '请输入谷歌验证码',
            trigger: 'blur'
          }]
        }
      };
    },
    created() {
    },
    watch: {
          // 监听 addOrUpdateVisible 改变
          visible (oldVal, newVal) {
            console.log(newVal);
            if(oldVal) {
              this.loading = true;
              googleAuthInfo(this.form)
                .then((res) => {
                  this.loading = false;
                  Object.assign(this.form, res, {

                  });
                })
                .catch((e) => {
                  this.loading = false;
                  this.$message.error(e.message);
                });
            }
          },

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
            googleAuthCreate(this.form)
              .then(() => {
                this.loading = false;
                this.$message.success('绑定成功');
                logout();
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
          google_code: '',
          google_code_url: '',
          verify_code: ''
        };
        this.$refs.form.resetFields();
        this.loading = false;
      }
    }
  };
</script>
