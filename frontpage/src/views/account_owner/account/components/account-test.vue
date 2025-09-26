<!-- 用户编辑弹窗 -->
<template>
  <el-dialog
    width="680px"
    :visible="visible"
    :close-on-click-modal="false"
    custom-class="ele-dialog-form"
    title="支付测试"
    @update:visible="updateVisible"
  >
    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
      <el-row>
          <el-form-item label="名称:" prop="name">
            <el-input
              disabled
              v-model="form.name"
            />
          </el-form-item>
          <el-form-item label="支付通道:" prop="channel_id">
            <channel-select v-model="form.channel_id">
            </channel-select>
          </el-form-item>
          <el-form-item label="金额:" prop="amount">
            <el-input
              v-model="form.amount"
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
    name: "",
    amount: 0,
    channel_id: null,
  };

  export default {
    name: 'AccountTest',
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
          amount: [
            {
              required: true,
              trigger: 'blur',
              message: '请输入金额',
            }
          ],
          channel_id: [
            {
              required: true,
              message: '请选择通道',
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
          api.account_create_test(data)
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
