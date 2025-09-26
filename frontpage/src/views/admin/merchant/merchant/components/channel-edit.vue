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
    <el-form ref="form" :model="form" :rules="rules" label-width="82px">
      <el-row>
          <el-form-item label="商户:" prop="username">
            {{data?.username}}
          </el-form-item>
          <el-form-item label="通道:" prop="channel_id">
            <channel-select v-model="form.channel_id"></channel-select>
          </el-form-item>
          <el-form-item label="费率:" prop="rate">
            <el-input
              :maxlength="20"
              v-model="form.rate"
              placeholder="请输入通道费率"
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
    user_id: null,
    channel_id: null,
    rate:0.0 
  };

  export default {
    name: 'MerchantChannelEdit',
    props: {
      // 弹窗是否打开
      visible: Boolean,
      // 修改回显的数据
      data: Object,
      channel: Object
    },
    data() {
      return {
        form: { ...DEFAULT_FORM },
        rules: {
          channel_id: [
            {
              required: true,
              message: '请选择通道',
              trigger: 'blur'
            }
          ],
          rate: [
            {
              required: true,
              message: '请输入费率',
              trigger: 'blur'
            }
          ],
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
          const saveOrUpdate = this.isUpdate ? api.user_merchant_channel_update : api.user_merchant_channel_create;
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
          if (this.channel) {
            this.$util.assignObject(this.form, {
              ...this.data, channel_id: this.channel?.id, rate: this.channel.pivot.rate
            });
            this.isUpdate = true;
          } else {
            this.$util.assignObject(this.form, {
              ...this.data
            });
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
