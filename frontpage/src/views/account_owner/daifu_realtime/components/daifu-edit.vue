<!-- 用户编辑弹窗 -->
<template>
  <el-dialog
    width="680px"
    :visible="visible"
    :close-on-click-modal="false"
    custom-class="ele-dialog-form"
    title="确认订单"
    @update:visible="updateVisible"
  >
    <el-form ref="form" :model="form" :rules="rules" label-width="82px">
      <el-row>
          <el-form-item label="订单号:" prop="daifu_id">
            <el-input
              disabled
              v-model="form.daifu_id"
            >
                </el-input>
          </el-form-item>
          <el-form-item label="金额:" prop="amount">
              <ele-text-primary style="font-size: 16px; margin-right: 12px;">
                  {{form.amount }}
              </ele-text-primary>
               <el-button icon="el-icon-copy"
                                        v-clipboard:copy="form.amount"
                                        v-clipboard:success="onCopy"
                                        v-clipboard:error="onError"
                   >复制</el-button>
          </el-form-item>
          <el-form-item label="账号名:" prop="account_name">
              <ele-text-primary style="font-size: 16px; margin-right: 12px;">
                  {{form.account_name }}
              </ele-text-primary>
               <el-button icon="el-icon-copy"
                                        v-clipboard:copy="form.account_name"
                                        v-clipboard:success="onCopy"
                                        v-clipboard:error="onError"
                   >复制</el-button>
          </el-form-item>
          <el-form-item label="账号:" prop="account">
              <ele-text-primary style="font-size: 16px; margin-right: 12px;">
                  {{form.account }}
              </ele-text-primary>
               <el-button icon="el-icon-copy"
                                        v-clipboard:copy="form.account"
                                        v-clipboard:success="onCopy"
                                        v-clipboard:error="onError"
                   >复制</el-button>
          </el-form-item>
          <el-form-item label="开户行:" prop="bank">
              <ele-text-primary style="font-size: 16px; margin-right: 12px;">
                  {{form.bank }}
              </ele-text-primary>
               <el-button icon="el-icon-copy"
                                        v-clipboard:copy="form.bank"
                                        v-clipboard:success="onCopy"
                                        v-clipboard:error="onError"
                   >复制</el-button>
          </el-form-item>
          <el-form-item label="转账截图:" prop="form.voucher">
              <el-row>
                  <ele-image-upload v-model="imgs" @upload="onUpload" :limit="1"/>
              </el-row>
          </el-form-item>
      </el-row>
    </el-form>
    <div slot="footer">
      <el-button type="warning" :loading="loading" @click="cancelDaifu"
        >撤单(让别人处理)
      </el-button>
      <el-button @click="updateVisible(false)">取消 </el-button>
      <el-button type="primary" :loading="loading" @click="save"
        >处理成功
      </el-button>
    </div>
  </el-dialog>
</template>

<script>
    import EleImageUpload from 'ele-admin/es/ele-image-upload';
  import * as api from '@/api/admin';
  const DEFAULT_FORM = {
      id: null,
      daifu_id: '',
      amount: 0,
      account: '',
      account_name: '',
      bank: '',
      voucher: ''
  };

  export default {
      components:{EleImageUpload},
    name: 'DaifuEdit',
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
          name: [
            {
              required: true,
            }
          ],
        },
        // 提交状态
        loading: false,
          imgs: [],
      };
    },
    methods: {
      /* 保存编辑 */
      save() {
        this.$refs['form'].validate((valid) => {
          if (!valid) {
            return false;
          }

            if (this.imgs.length<1) {
            return false;
          }

          this.loading = true;
          const data = {
              ...this.form, 
              voucher: this.imgs[0].url
          };

          api.daifu_confirm(data)
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
        cancelDaifu() {
            this.loading = true;
            const data = {
                ...this.form, 
            };

            api.daifu_cancel(data)
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
        },
        onUpload(item) {
            // item 包含的字段参考前面说明
            console.log('item:', item);
            item.status = 'uploading';

            api.upload(item.file).then((res) => {
                item.status = 'done';
                item.url = res.url;
                // 如果你上传的不是图片格式, 建议将 url 字段作为缩略图, 再添加其它字段作为最后提交数据
                //item.url = res.data.data.thumbnail;  // 也可以不赋值 url 字段, 默认会显示为一个文件图标
                item.fileUrl = res.url;
            }).catch((e) => {
                item.status = 'exception';
                console.log(e);
            });
        },
      /* 更新visible */
      updateVisible(value) {
        this.$emit('update:visible', value);
      },
        onCopy: function () {
            this.$message.success('复制成功');
        },
        onError: function () {
            this.$message.error('复制失败');
        }
    },
    watch: {
      visible(visible) {
        if (visible) {
          if (this.data) {
            this.$util.assignObject(this.form, {
              ...this.data,
            });

              if(this.data.voucher.trim().length == 0){
                  this.imgs = [];
              }
              else {
                  this.imgs = [
                      {
                          uid: 1,
                          url: this.data.voucher
                      }
                  ];
              }
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
