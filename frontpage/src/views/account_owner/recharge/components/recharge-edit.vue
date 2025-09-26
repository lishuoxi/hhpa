<!-- 用户编辑弹窗 -->
<template>
  <el-dialog
    width="680px"
    :visible="visible"
    :close-on-click-modal="false"
    custom-class="ele-dialog-form"
    title="新建充值"
    @update:visible="updateVisible"
  >
    <el-form ref="form" :model="form" :rules="rules" label-width="82px">
      <el-row>
          <el-form-item label="金额:" prop="amount">
            <el-input
              clearable
              :maxlength="20"
              v-model="form.amount"
              placeholder="请输入金额"
            />
          </el-form-item>
          <el-form-item label="上传文件:" prop="receipts">
              <el-row>
                  <el-col :span="4" >
                      <img :src="form.receipts" width="60px" height="60px" />
                  </el-col>
                  <el-col :span="10">
                        <el-upload
                          drag
                          ref="upload"
                          class="ele-block"
                          v-loading="loading"
                          accept=".jpg,.png"
                          :show-file-list="true"
                          :before-upload="doUpload"
                          :auto-upload="true"
                          :multiple = 'false'
                        >
                      <i class="el-icon-upload"></i>
                      <div class="el-upload__text">将文件拖到此处, 或 <em>点击上传</em></div>
                      <div class="el-upload__tip ele-text-center" slot="tip">
                          <span>只能上传png、jpg文件 </span>
                      </div>
                    </el-upload>
                  </el-col>
              </el-row>
		  </el-form-item>
          <el-form-item label="备注:" prop="note">
            <el-input
              :maxlength="200"
              v-model="form.note"
              placeholder="请输入备注"
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
    amount: 0.0,
    receipts: '',
    note: '',
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
        form: { ...DEFAULT_FORM },
        // 表单验证规则
        rules: {
          amount: [
            {
              required: true,
              trigger: 'blur',
              message: '请输入充值金额',
            }
          ],
          receipts: [
            {
              required: true,
              message: '请上传凭证',
              trigger: 'blur'
            }
          ],
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
          api.recharge_create(data)
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
      },
      doUpload(file) {
        this.loading = true;

        api.upload(file)
         .then((data) => {
              this.loading = false;
              this.$message.success('上传成功');

             this.form = { ...this.form, receipts:data.url };

             console.log(data);

          })
          .catch((e) => {
              this.loading = false;
              this.$message.error(e.message);
           });

            return false;
        },
    },
      watch: {
          visible(visible) {
              if (!visible) {
                  this.$refs['form'].clearValidate();
                  this.form = { ...DEFAULT_FORM };
              }
          }
      }
  };
</script>

<style scoped></style>
