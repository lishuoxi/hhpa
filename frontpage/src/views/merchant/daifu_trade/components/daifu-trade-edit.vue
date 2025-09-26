<!-- 用户编辑弹窗 -->
<template>
  <el-dialog
    width="680px"
    :visible="visible"
    :close-on-click-modal="false"
    custom-class="ele-dialog-form"
    title="发起代付"
    @update:visible="updateVisible"
  >
    <el-form ref="form" :model="form" :rules="rules" label-width="102px">
      <el-row>
          <el-form-item label="可提现金额:" >
            <el-input
              disabled
              v-model="balance"
            />
          </el-form-item>
          <el-form-item label="金额:" prop="amount">
            <el-input
              clearable
              :maxlength="20"
              v-model="form.amount"
              placeholder="请输入代付金额"
            />
          </el-form-item>
          <el-form-item label="收款账号:" prop="account">
            <el-input
              clearable
              :maxlength="40"
              v-model="form.account"
              placeholder="请输入收款账号"
            />
          </el-form-item>
          <el-form-item label="收款账号名:" prop="account_name">
            <el-input
              clearable
              :maxlength="60"
              v-model="form.account_name"
              placeholder="请输入收款账号名"
            />
          </el-form-item>
          <el-form-item label="开户行:" prop="bank_name">
            <el-input
              clearable
              :maxlength="40"
              v-model="form.bank_name"
              placeholder="请输入开户行"
            />
          </el-form-item>
          <el-form-item label="备注:" prop="note">
            <el-input
              clearable
              :maxlength="20"
              v-model="form.note"
              placeholder="请输入备注"
            />
          </el-form-item>

      </el-row>
    </el-form>
    <div slot="footer">
      <el-button @click="updateVisible()">取消 </el-button>
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
    amount: 0.0,
    account: '',
    account_name: '',
    bank_name: '',
    note: '',
  };

  export default {
    name: 'MerchantDaifuTradeEdit',
    props: {
      // 弹窗是否打开
      visible: Boolean,
      // 修改回显的数据
      data: Object,
    },
      created() {
          api.user_get_balance()
            .then((data) => {
                this.balance = data.balance;
            })
            .catch(() => {
            });

      },
    data() {
      return {
        // 表单数据
        form: { ...DEFAULT_FORM },
        balance: 0,
        // 表单验证规则
        rules: {
          amount: [
            {
            required: true,
            message: '请输入金额',
            trigger: 'blur'
            }
          ],
          account: [
            {
            required: true,
            message: '请输入账户',
            trigger: 'blur'
            }
          ],
          account_name: [
            {
              required: true,
              message: '请输入账户名',
              trigger: 'blur'
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
          api.daifu_trade_create(data)
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
              ...this.data
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
