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
          <el-input clearable :maxlength="20" v-model="form.name" placeholder="请输入名称" />
        </el-form-item>
        <el-form-item label="所有者:" prop="account_owner_id">
          <user-select v-model="form.account_owner_id" :role-id="4" />
        </el-form-item>
        <el-form-item label="支付码类型:" prop="account_type_id">
          <account-type-select v-model="form.account_type_id" />
        </el-form-item>

        <el-form-item label="每日收款金额:" prop="amount_day_limit">
          <el-input clearable :maxlength="20" v-model="form.amount_day_limit" placeholder="为0则不限制" />
        </el-form-item>
        <el-form-item label="每日笔数限制:" prop="times_day_limit">
          <el-input clearable :maxlength="20" v-model="form.times_day_limit" placeholder="为0则不限制" />
        </el-form-item>
        <el-form-item label="单笔最小金额:" prop="amount_min_limit">
          <el-input clearable :maxlength="20" v-model="form.amount_min_limit" placeholder="为0则不限制" />
        </el-form-item>
        <el-form-item label="单笔最大金额:" prop="amount_max_limit">
          <el-input clearable :maxlength="20" v-model="form.amount_max_limit" placeholder="为0则不限制" />
        </el-form-item>

        <template v-if="form.account_type_id==1">
          <el-form-item label="支付宝appid:" prop="param1">
            <el-input clearable :maxlength="120" v-model="form.param1" placeholder="请输入支付宝appid" />
          </el-form-item>
          <el-form-item label="支付宝公钥:" prop="param4">
            <el-input v-model="form.param4" placeholder="请输入支付宝公钥" :rows="4" type="textarea" />
          </el-form-item>
          <el-form-item label="支付宝私钥:" prop="param5">
            <el-input v-model="form.param5" placeholder="请输入支付宝私钥" :rows="4" type="textarea" />
          </el-form-item>
        </template>

        <template v-if="form.account_type_id==2">
          <el-form-item label="支付宝二维码:" prop="param1">
            <el-row>
              <el-col :span="4">
                <img :src="form.param1" width="60" height="60" />
              </el-col>
              <el-col :span="10">
                <el-upload drag ref="upload" class="ele-block" v-loading="loading" accept=".jpg,.png"
                           :show-file-list="true" :before-upload="doUpload1" :auto-upload="true" :multiple="false">
                  <i class="el-icon-upload"></i>
                  <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                  <div class="el-upload__tip ele-text-center" slot="tip"><span>只能上传png、jpg文件</span></div>
                </el-upload>
              </el-col>
            </el-row>
          </el-form-item>
          <el-form-item label="二维码链接:" prop="param2">
            <el-input v-model="form.param2" placeholder="请输入二维码链接" />
          </el-form-item>
        </template>

        <template v-if="form.account_type_id==3">
          <el-form-item label="开户账号名称:" prop="param1">
            <el-input clearable :maxlength="120" v-model="form.param1" placeholder="请输入开户账号名称" />
          </el-form-item>
          <el-form-item label="开户行:" prop="param2">
            <el-input clearable :maxlength="120" v-model="form.param2" placeholder="请输入开户行" />
          </el-form-item>
          <el-form-item label="卡号:" prop="param3">
            <el-input clearable :maxlength="120" v-model="form.param3" placeholder="请输入卡号" />
          </el-form-item>
        </template>

        <!-- 支付宝个人码，上传后自动解析二维码链接到 param2 -->
        <template v-if="form.account_type_id==6">
          <el-form-item label="支付宝二维码:" prop="param1">
            <el-row>
              <el-col :span="4">
                <img :src="form.param1" width="60" height="60" />
              </el-col>
              <el-col :span="10">
                <el-upload drag ref="upload" class="ele-block" v-loading="loading" accept=".jpg,.png"
                           :show-file-list="true" :before-upload="doUploadAlipayPersonal" :auto-upload="true" :multiple="false">
                  <i class="el-icon-upload"></i>
                  <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                  <div class="el-upload__tip ele-text-center" slot="tip"><span>只能上传 png、jpg 文件</span></div>
                </el-upload>
              </el-col>
            </el-row>
          </el-form-item>
          <el-form-item label="二维码链接:" prop="param2">
            <el-input v-model="form.param2" placeholder="自动解析二维码后的链接" />
          </el-form-item>
        </template>

        <template v-if="form.account_type_id==8">
          <el-form-item label="支付宝appid:" prop="param4">
            <el-input clearable :maxlength="120" v-model="form.param4" placeholder="请输入支付宝appid" />
          </el-form-item>
          <el-form-item label="应用公钥证书:" prop="param1">
            <el-input v-model="form.param1" placeholder="请输入文件链接" />
            <el-upload drag ref="upload" class="ele-block" v-loading="loading"
                       :show-file-list="true" :before-upload="doUpload1" :auto-upload="true" :multiple="false">
              <i class="el-icon-upload"></i>
              <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
              <div class="el-upload__tip ele-text-center" slot="tip"><span>只能上传证书文件</span></div>
            </el-upload>
          </el-form-item>
          <el-form-item label="支付宝公钥证书:" prop="param2">
            <el-input v-model="form.param2" placeholder="请输入文件链接" />
            <el-upload drag ref="upload" class="ele-block" v-loading="loading"
                       :show-file-list="true" :before-upload="doUpload2" :auto-upload="true" :multiple="false">
              <i class="el-icon-upload"></i>
              <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
              <div class="el-upload__tip ele-text-center" slot="tip"><span>只能上传证书文件</span></div>
            </el-upload>
          </el-form-item>
          <el-form-item label="支付宝根证书:" prop="param3">
            <el-input v-model="form.param3" placeholder="请输入文件链接" />
            <el-upload drag ref="upload" class="ele-block" v-loading="loading"
                       :show-file-list="true" :before-upload="doUpload3" :auto-upload="true" :multiple="false">
              <i class="el-icon-upload"></i>
              <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
              <div class="el-upload__tip ele-text-center" slot="tip"><span>只能上传证书文件</span></div>
            </el-upload>
          </el-form-item>
          <el-form-item label="支付宝私钥:" prop="param5">
            <el-input v-model="form.param5" placeholder="请输入支付宝私钥" :rows="4" type="textarea" />
          </el-form-item>
        </template>

      </el-row>
    </el-form>
    <div slot="footer">
      <el-button @click="updateVisible(false)">取消</el-button>
      <el-button type="primary" :loading="loading" @click="save">保存</el-button>
    </div>
  </el-dialog>
</template>

<script>
import * as api from '@/api/admin';

const DEFAULT_FORM = {
  id: null,
  account_owner_id: 0,
  account_type_id: 0,
  name: '',
  amount_day_limit: 0,
  times_day_limit: 0,
  amount_max_limit: 0,
  amount_min_limit: 0,
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
    visible: Boolean,
    data: Object,
  },
  data() {
    return {
      form: { ...DEFAULT_FORM },
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
            },
          },
        ],
        account_owner_id: [
          { required: true, message: '请选择所有者', trigger: 'blur' },
        ],
        account_type_id: [
          { required: true, message: '请选择支付码类型', trigger: 'blur' },
        ],
      },
      loading: false,
      isUpdate: false,
    };
  },
  methods: {
    save() {
      this.$refs['form'].validate((valid) => {
        if (!valid) return;
        this.loading = true;
        const data = { ...this.form };
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
          this.form['param' + num] = data.url;
        })
        .catch((e) => {
          this.loading = false;
          this.$message.error(e.message);
        });
      return false;
    },
    doUpload1(file) { return this.doUpload(file, '1'); },
    doUpload2(file) { return this.doUpload(file, '2'); },
    doUpload3(file) { return this.doUpload(file, '3'); },

    async doUploadAlipayPersonal(file) {
      this.loading = true;
      try {
        // 先本地解析文件，避免跨域导致的 Canvas 污染
        const text = await this.decodeQrFromFile(file);
        if (text) {
          this.form.param2 = text;
        } else {
          throw new Error('未能解析二维码，请更换更清晰的图片');
        }

        // 再上传文件获取线上地址用于预览/存储
        const data = await api.upload(file);
        this.form = { ...this.form };
        this.form.param1 = data.url;
        this.$message.success('上传并解析成功');
      } catch (e) {
        this.$message.error(e.message || '二维码解析失败');
      } finally {
        this.loading = false;
      }
      return false;
    },
    async decodeQrFromFile(file) {
      // 1) BarcodeDetector + createImageBitmap 直接本地识别
      try {
        if (window && window.BarcodeDetector && window.createImageBitmap) {
          const detector = new window.BarcodeDetector({ formats: ['qr_code'] });
          const bmp = await createImageBitmap(file);
          const codes = await detector.detect(bmp);
          const text = codes?.[0]?.rawValue;
          if (text) return text;
        }
      } catch (e) { /* 忽略，进入下一种方式 */ }

      // 2) jsQR 本地识别
      try {
        let jsqrMod;
        try { jsqrMod = await import(/* webpackChunkName: "jsqr-optional" */ 'jsqr'); } catch (_) { void 0; }
        const jsQR = (jsqrMod && (jsqrMod.default || jsqrMod));
        if (jsQR) {
          const dataUrl = await new Promise((resolve, reject) => {
            const fr = new FileReader();
            fr.onload = () => resolve(fr.result);
            fr.onerror = () => reject(new Error('文件读取失败'));
            fr.readAsDataURL(file);
          });
          const img = await new Promise((resolve, reject) => {
            const i = new Image();
            i.onload = () => resolve(i);
            i.onerror = () => reject(new Error('图片加载失败'));
            i.src = dataUrl;
          });
          const canvas = document.createElement('canvas');
          canvas.width = img.naturalWidth || img.width;
          canvas.height = img.naturalHeight || img.height;
          const ctx = canvas.getContext('2d');
          ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
          const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
          const code = jsQR(imageData.data, imageData.width, imageData.height);
          if (code && code.data) return code.data;
        }
      } catch (e) { /* 忽略，返回空 */ }

      return '';
    },
    async decodeQrToParam2(imageUrl) {
      const loadImage = (url) => new Promise((resolve, reject) => {
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = () => resolve(img);
        img.onerror = () => reject(new Error('图片加载失败'));
        img.src = url;
      });

      // 1) 浏览器 BarcodeDetector
      try {
        if (window && window.BarcodeDetector) {
          const detector = new window.BarcodeDetector({ formats: ['qr_code'] });
          const img = await loadImage(imageUrl);
          const codes = await detector.detect(img);
          const text = codes?.[0]?.rawValue;
          if (text) {
            this.form.param2 = text;
            this.$message.success('二维码解析成功');
            return;
          }
          throw new Error('本地解码未识别二维码');
        }
      } catch (e) { void 0; }

      // 2) 可选 jsQR（如已安装）
      try {
        let jsqrMod;
        try { jsqrMod = await import(/* webpackChunkName: "jsqr-optional" */ 'jsqr'); } catch (_) { void 0; }
        const jsQR = (jsqrMod && (jsqrMod.default || jsqrMod));
        if (jsQR) {
          const img = await loadImage(imageUrl);
          const canvas = document.createElement('canvas');
          canvas.width = img.naturalWidth || img.width;
          canvas.height = img.naturalHeight || img.height;
          const ctx = canvas.getContext('2d');
          ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
          const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
          const code = jsQR(imageData.data, imageData.width, imageData.height);
          if (code && code.data) {
            this.form.param2 = code.data;
            this.$message.success('二维码解析成功');
            return;
          }
          throw new Error('jsQR 未识别二维码');
        }
      } catch (e) { void 0; }

      // 3) 远程兜底
      try {
        const apiUrl = 'https://api.qrserver.com/v1/read-qr-code/?fileurl=' + encodeURIComponent(imageUrl);
        const res = await fetch(apiUrl);
        if (!res.ok) throw new Error('二维码解析服务不可用');
        const json = await res.json();
        const data = json?.[0]?.symbol?.[0];
        const text = data?.data;
        const err = data?.error;
        if (!text || err) {
          throw new Error('无法解析二维码，请更换清晰图片');
        }
        this.form.param2 = text;
        this.$message.success('二维码解析成功');
      } catch (e) {
        this.$message.error(e.message || '二维码解析失败');
      }
    },

    updateVisible(value) { this.$emit('update:visible', value); },
  },
  watch: {
    visible(visible) {
      if (visible) {
        if (this.data) {
          this.$util.assignObject(this.form, { ...this.data });
          this.isUpdate = true;
        } else {
          this.isUpdate = false;
        }
      } else {
        this.$refs['form']?.clearValidate?.();
        this.form = { ...DEFAULT_FORM };
      }
    },
  },
};
</script>

<style scoped></style>
