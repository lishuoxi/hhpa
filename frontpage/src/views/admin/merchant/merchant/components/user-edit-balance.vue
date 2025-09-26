<!-- 用户编辑弹窗 -->
<template>
  <el-dialog
    width="680px"
    :visible="visible"
    :close-on-click-modal="false"
    custom-class="ele-dialog-form"
    title="修改余额"
    @update:visible="updateVisible"
  >
    <el-form ref="form" :model="form" :rules="rules" label-width="160px">
      <el-row>
          <el-form-item label="用户账号:" prop="username">
            <el-input
              disabled
              v-model="form.username"
            />
          </el-form-item>
          <el-form-item label="变动金额:">
            <el-input
              :maxlength="20"
              v-model="form.amount"
              placeholder="请输入要变动的金额,可填入负数"
            />
          </el-form-item>
          <el-form-item label="锁定余额变动:">
            <el-input
              :maxlength="20"
              v-model="form.amount_lock"
              placeholder="请输入要变动的金额,可填入负数"
            />
          </el-form-item>
          <el-form-item label="代付余额变动:">
            <el-input
              :maxlength="20"
              v-model="form.daifu_amount"
              placeholder="请输入要变动的金额,可填入负数"
            />
          </el-form-item>
          <el-form-item label="代付锁定余额变动:">
            <el-input
              :maxlength="20"
              v-model="form.daifu_amount_lock"
              placeholder="请输入要变动的金额,可填入负数"
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
    amount: '',
    daifu_amount: '',
    amount_lock: '',
    daifu_amount_lock: '',
  };

  export default {
    name: 'MerchantUserEditBalance',
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
        },
        // 提交状态
        loading: false,
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
          api.user_edit_balance(data)
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
