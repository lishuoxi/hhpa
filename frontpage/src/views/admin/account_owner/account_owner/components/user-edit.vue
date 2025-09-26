<!-- 用户编辑弹窗 -->
<template>
  <el-dialog
    width="680px"
    :visible="visible"
    :close-on-click-modal="false"
    custom-class="ele-dialog-form"
    :title="isUpdate ? '修改用户' : '添加用户'"
    @update:visible="updateVisible"
  >
    <el-form ref="form" :model="form" :rules="rules" label-width="82px">
      <el-row>
          <el-form-item label="用户账号:" prop="username">
            <el-input
              clearable
              :maxlength="20"
              :disabled="isUpdate"
              v-model="form.username"
              placeholder="请输入用户账号"
            />
          </el-form-item>
          <el-form-item label="昵称:" prop="realname">
            <el-input
              clearable
              :maxlength="20"
              v-model="form.realname"
              placeholder="请输入昵称"
            />
          </el-form-item>
          <el-form-item label="上级用户:" prop="pid">
            <user-select
              v-model="form.pid"
              role-id="4"
            />
          </el-form-item>
          <el-form-item v-if="!isUpdate" label="登录密码:" prop="password">
            <el-input
              show-password
              :maxlength="20"
              v-model="form.password"
              placeholder="请输入登录密码"
            />
          </el-form-item>
      </el-row>
    </el-form>
    <div slot="footer">
      <el-button @click="updateVisible(false)">取消 </el-button>
      <el-button type="primary" :loading="loading" @click="save"
        >保存
      </el-button>
    </div>
  </el-dialog>
</template>

<script>

  import * as api from '@/api/admin';
  const DEFAULT_FORM = {
    id: null,
    username: '',
    realname: '',
    password: '',
    pid: '',
    role_id: 4,
  };

  export default {
    name: 'UserEdit',
    props: {
      // 弹窗是否打开
      visible: Boolean,
      // 修改回显的数据
      data: Object
    },
    data() {
      return {
        // 表单数据
        form: { ...DEFAULT_FORM},
        // 表单验证规则
        rules: {
          username: [
            {
              required: true,
              trigger: 'blur',
              validator: (rule, value, callback) => {
                if (!value) {
                  return callback(new Error('请输入用户账号'));
                }
                api.user_check_existence('username', value, this.data?.username)
                  .then(() => {
                    callback(new Error('账号已经存在'));
                  })
                  .catch(() => {
                    callback();
                  });
              }
            }
          ],
          realname: [
            {
              required: true,
              message: '请输入真实姓名',
              trigger: 'blur'
            }
          ],
        },
        // 提交状态
        loading: false,
        // 是否是修改
        isUpdate: false,
      };
    },
    methods: {
      /* 保存编辑 */
      save() {

        this.$refs['form'].validate((valid) => {
          if (!valid) {
            return false;
          }
          this.loading = true;
          const data = {
            ...this.form,
          };

          const saveOrUpdate = this.isUpdate ? api.user_update : api.user_create;
          saveOrUpdate(data)
            .then(() => {
              this.loading = false;
              this.$message.success('操作成功');
              this.updateVisible(false);
              this.$emit('done');
            })
            .catch((e) => {
              this.loading = false;
              this.$message.error(e.message);
            });
        });
      },
      /* 更新visible */
      updateVisible(value) {
        this.$emit('update:visible', value);
      }
    },
    watch: {
      visible(visible) {
        if (visible) {
          if (this.data) {
            this.$util.assignObject(this.form, {
              ...this.data,
              password: ''
            });
            this.isUpdate = true;
          } else {
            this.isUpdate = false;
          }
        } else {
          this.$refs['form'].clearValidate();
          this.form = { ...DEFAULT_FORM };
        }
      }
    }
  };
</script>

<style scoped></style>
