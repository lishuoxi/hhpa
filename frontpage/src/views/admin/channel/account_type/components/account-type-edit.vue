<!-- 用户编辑弹窗 -->
<template>
  <el-dialog
    width="680px"
    :visible="visible"
    :close-on-click-modal="false"
    custom-class="ele-dialog-form"
    :title="isUpdate ? '修改支付码类型' : '添加支付码类型'"
    @update:visible="updateVisible"
  >
    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
      <el-row>
          <el-form-item label="名称:" prop="name">
            <el-input
              clearable
              :maxlength="32"
              v-model="form.name"
              placeholder="请输入名称"
            />
          </el-form-item>
      </el-row>
      <el-row>
          <el-form-item label="代码:" prop="code">
            <el-input
              clearable
              :maxlength="20"
              v-model="form.code"
              placeholder="请输入代码"
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
    name: '',
    code: ''
  };

  export default {
    name: 'AccountTypeEdit',
    props: {
      // 弹窗是否打开
      visible: Boolean,
      // 修改回显的数据
      data: Object
    },
    data() {
      return {
        // 表单数据
        form: { ...DEFAULT_FORM },
        // 表单验证规则
        rules: {
          name: [
            {
              required: true,
              trigger: 'blur',
              validator: (rule, value, callback) => {
                if (!value) {
                  return callback(new Error('请输入名称'));
                }
                api.account_type_check_existence({field: 'name', value: this.data?.name, id:this.data?.id})
                .then(() => {
                    callback(new Error('类型已经存在'));
                })
                .catch(() => {
                    callback();
                });
              }
            }
          ],
          code: [
            {
              required: true,
              trigger: 'blur',
              validator: (rule, value, callback) => {
                if (!value) {
                  return callback(new Error('请输入代码'));
                }
                api.account_type_check_existence({field: 'code', value: this.data?.code, id:this.data?.id})
                .then(() => {
                    callback(new Error('代码已经存在'));
                })
                .catch(() => {
                    callback();
                });
              }
            }
          ]
        },
        // 提交状态
        loading: false,
        // 是否是修改
        isUpdate: false
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
          const saveOrUpdate = this.isUpdate ? api.account_type_update : api.account_type_create;
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
