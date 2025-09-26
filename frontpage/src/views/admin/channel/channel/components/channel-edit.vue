<!-- 用户编辑弹窗 -->
<template>
  <el-dialog
    width="680px"
    :visible="visible"
    :close-on-click-modal="false"
    custom-class="ele-dialog-form"
    :title="isUpdate ? '修改通道' : '添加通道'"
    @update:visible="updateVisible"
  >
    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
      <el-row>
          <el-form-item label="名称:" prop="name">
            <el-input
              clearable
              :maxlength="20"
              v-model="form.name"
              placeholder="请输入名称:"
            />
          </el-form-item>
          <el-form-item label="编码:" prop="code">
            <el-input
              clearable
              :maxlength="20"
              v-model="form.code"
              placeholder="请输入编码"
            />
          </el-form-item>
          <el-form-item label="单笔最大小金额:" prop="amount_min_limit">
            <el-input
              clearable
              :maxlength="20"
              v-model="form.amount_min_limit"
              placeholder="请输入单笔最小金额,0则不限制"
            />
          </el-form-item>

          <el-form-item label="单笔最大金额:" prop="amount_max_limit">
            <el-input
              clearable
              :maxlength="20"
              v-model="form.amount_max_limit"
              placeholder="请输入单笔最大金额,0则不限制"
            />
          </el-form-item>

          <el-form-item label="单日限量:" prop="amount_day_limit">
            <el-input
              clearable
              :maxlength="20"
              v-model="form.amount_day_limit"
              placeholder="请输入单日限量,0则不限制"
            />
          </el-form-item>

          <el-form-item label="固定金额:" prop="fixed_amounts">
            <el-input
              v-model="form.fixed_amounts"
              placeholder="请输入单日限量,为空则不限制,金额间用逗号分开"
              :rows="4"
              type="textarea"
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
    code: '',
    amount_max_limit: 0,
    amount_min_limit: 0,
    amount_day_limit: 0,
    fixed_amounts: '',
  };

  export default {
    name: 'ChannelEdit',
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
          code: [
            {
              required: true,
              trigger: 'blur',
              validator: (rule, value, callback) => {
                if (!value) {
                  return callback(new Error('请输入通道编码'));
                }
                api.channel_check_existence({field: 'code', value: this.data?.code, id:this.data?.id})
                  .then(() => {
                    callback(new Error('编码已经存在'));
                  })
                  .catch(() => {
                    callback();
                  });
              }
            }
          ],
          name: [
            {
              required: true,
              message: '请输入通道名称',
              trigger: 'blur',
              validator: (rule, value, callback) => {
                if (!value) {
                  return callback(new Error('请输入通道名称'));
                }
                api.channel_check_existence({field: 'name', value: this.data?.name, id:this.data?.id})
                  .then(() => {
                    callback(new Error('通道名称已经存在'));
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
          const saveOrUpdate = this.isUpdate ? api.channel_update : api.channel_create;
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
