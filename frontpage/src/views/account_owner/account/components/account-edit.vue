<!-- 用户编辑弹窗 -->
<template>
  <el-dialog
    width="680px"
    :visible="visible"
    :close-on-click-modal="false"
    custom-class="ele-dialog-form"
    :title="isUpdate ? '修改支付码' : '添加支付码'"
    @update:visible="updateVisible"
  >
    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
      <el-row>
          <el-form-item label="名称:" prop="name">
            <el-input
              clearable
              :maxlength="20"
              v-model="form.name"
              placeholder="请输入名称"
            />
          </el-form-item>
          <el-form-item label="支付码类型:" prop="account_type_id">
            <account-type-select v-model="form.account_type_id">
            </account-type-select>
          </el-form-item>

          <el-form-item label="每日收款金额:" prop="amount_day_limit">
            <el-input
              clearable
              :maxlength="20"
              v-model="form.amount_day_limit"
              placeholder="为0则不限制"
            />
          </el-form-item>

          <el-form-item label="每日笔数限制:" prop="times_day_limit">
            <el-input
              clearable
              :maxlength="20"
              v-model="form.times_day_limit"
              placeholder="为0则不显示"
            />
          </el-form-item>

          <el-form-item label="单笔最小金额:" prop="amount_min_limit">
            <el-input
              clearable
              :maxlength="20"
              v-model="form.amount_min_limit"
              placeholder="为0则不显示"
            />
          </el-form-item>

          <el-form-item label="单笔最大金额:" prop="amount_max_limit">
            <el-input
              clearable
              :maxlength="20"
              v-model="form.amount_max_limit"
              placeholder="为0则不显示"
            />
          </el-form-item>

          <template v-if="form.account_type_id==1">
            <el-form-item label="支付宝appid:" prop="param1">
             <el-input
               clearable
               :maxlength="120"
               v-model="form.param1"
               placeholder="请输入支付宝appid"
             />
            </el-form-item>

            <el-form-item label="支付宝公钥:" prop="param4">
              <el-input
                v-model="form.param4"
                placeholder="请输入支付宝公钥"
                :rows="4"
                type="textarea"
              />
            </el-form-item>

            <el-form-item label="支付宝私钥:" prop="param5">
              <el-input
                v-model="form.param5"
                placeholder="请输入支付宝私钥"
                :rows="4"
                type="textarea"
              />
            </el-form-item>
          </template>

          <template v-if="form.account_type_id==2">
            <el-form-item label="支付宝二维码:" prop="param1">
              <el-row>
                  <el-col :span="4" >
                      <img :src="form.param1" width="60px" height="60px" />
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

            <el-form-item label="二维码链接:" prop="param2">
              <el-input
                v-model="form.param2"
                placeholder="请输入二维码链接"
              />
            </el-form-item>
          </template>

          <template v-if="form.account_type_id==3">
            <el-form-item label="开户账号名称:" prop="param1">
             <el-input
               clearable
               :maxlength="120"
               v-model="form.param1"
               placeholder="请输入开户账号名称"
             />
            </el-form-item>

            <el-form-item label="开户行:" prop="param2">
             <el-input
               clearable
               :maxlength="120"
               v-model="form.param2"
               placeholder="请输入开户行"
             />
            </el-form-item>

            <el-form-item label="卡号" prop="param3">
             <el-input
               clearable
               :maxlength="120"
               v-model="form.param3"
               placeholder="请输入卡号"
             />
            </el-form-item>
          </template>

          <template v-if="form.account_type_id==6">
            <el-form-item label="支付宝二维码:" prop="param1">
              <el-row>
                  <el-col :span="4" >
                      <img :src="form.param1" width="60px" height="60px" />
                  </el-col>
                  <el-col :span="10">
                        <el-upload
                          drag
                          ref="upload"
                          class="ele-block"
                          v-loading="loading"
                          accept=".jpg,.png"
                          :show-file-list="true"
                          :before-upload="doUpload1"
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

            <el-form-item label="二维码链接:" prop="param2">
              <el-input
                v-model="form.param2"
                placeholder="请输入二维码链接"
              />
            </el-form-item>
          </template>

          <template v-if="form.account_type_id==8">
            <el-form-item label="支付宝appid:" prop="param4">
             <el-input
               clearable
               :maxlength="120"
               v-model="form.param4"
               placeholder="请输入支付宝appid"
             />
            </el-form-item>
            <el-form-item label="应用公钥证书:" prop="param1">
              <el-row>
                      <el-input
                              v-model="form.param1"
                              placeholder="请输入文件链接"
                              />
              </el-row>
              <el-row>
                        <el-upload
                          drag
                          ref="upload"
                          class="ele-block"
                          v-loading="loading"
                          :show-file-list="true"
                          :before-upload="doUpload1"
                          :auto-upload="true"
                          :multiple = 'false'
                        >
                      <i class="el-icon-upload"></i>
                      <div class="el-upload__text">将文件拖到此处, 或 <em>点击上传</em></div>
                      <div class="el-upload__tip ele-text-center" slot="tip">
                          <span>只能上传证书文件 </span>
                      </div>
                    </el-upload>
              </el-row>
            </el-form-item>

            <el-form-item label="支付宝公钥证书:" prop="param2">
              <el-row>
                      <el-input
                              v-model="form.param2"
                              placeholder="请输入文件链接"
                              />
              </el-row>
              <el-row>
                        <el-upload
                          drag
                          ref="upload"
                          class="ele-block"
                          v-loading="loading"
                          :show-file-list="true"
                          :before-upload="doUpload2"
                          :auto-upload="true"
                          :multiple = 'false'
                        >
                      <i class="el-icon-upload"></i>
                      <div class="el-upload__text">将文件拖到此处, 或 <em>点击上传</em></div>
                      <div class="el-upload__tip ele-text-center" slot="tip">
                          <span>只能上传证书文件 </span>
                      </div>
                    </el-upload>
              </el-row>
            </el-form-item>

            <el-form-item label="支付宝根证书:" prop="param3">
              <el-row>
                      <el-input
                              v-model="form.param3"
                              placeholder="请输入文件链接"
                              />
              </el-row>
              <el-row>
                        <el-upload
                          drag
                          ref="upload"
                          class="ele-block"
                          v-loading="loading"
                          :show-file-list="true"
                          :before-upload="doUpload3"
                          :auto-upload="true"
                          :multiple = 'false'
                        >
                      <i class="el-icon-upload"></i>
                      <div class="el-upload__text">将文件拖到此处, 或 <em>点击上传</em></div>
                      <div class="el-upload__tip ele-text-center" slot="tip">
                          <span>只能上传证书文件 </span>
                      </div>
                    </el-upload>
              </el-row>
            </el-form-item>

            <el-form-item label="支付宝私钥:" prop="param5">
              <el-input
                v-model="form.param5"
                placeholder="请输入支付宝私钥"
                :rows="4"
                type="textarea"
              />
            </el-form-item>
          </template>

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
    account_type_id: 0,
    name: '',
  amount_day_limit:0,
  times_day_limit:0,
  amount_max_limit:0,
  amount_min_limit:0,
    param1: '',
    param2: '',
    param3: '',
    param4: '',
    param5: '',
    param6: '',
  };

  export default {
    name: 'AccountEdit',
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
                api.account_check_existence('name', value, this.data?.name)
                  .then(() => {
                    callback(new Error('名称已经存在, 不能重复'));
                  })
                  .catch(() => {
                    callback();
                  });
              }
            }
          ],
          account_type_id: [
            {
              required: true,
              message: '请选择支付码类型',
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
          const saveOrUpdate = this.isUpdate ? api.account_update : api.account_create;
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
      doUpload(file, num) {
        this.loading = true;

        api.upload(file)
         .then((data) => {
              this.loading = false;
              this.$message.success('上传成功');

             this.form = { ...this.form };
             this.form["param"+num] = data.realpath;

             console.log(data);

          })
          .catch((e) => {
              this.loading = false;
              this.$message.error(e.message);
           });

            return false;
        },
      doUpload1(file) {
            return this.doUpload(file, "1");
        },

      doUpload2(file) {
            return this.doUpload(file, "2");
        },

      doUpload3(file) {
            return this.doUpload(file, "3");
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
