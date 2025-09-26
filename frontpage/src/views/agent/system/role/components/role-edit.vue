<!-- 角色编辑弹窗 -->
<template>
  <el-dialog
    width="460px"
    :visible="visible"
    :close-on-click-modal="false"
    :title="isUpdate ? '修改角色' : '添加角色'"
    @update:visible="updateVisible"
  >
    <el-form ref="form" :model="form" :rules="rules" label-width="82px">
      <el-form-item label="角色名称:" prop="name">
        <el-input
          clearable
          :maxlength="20"
          v-model="form.name"
          placeholder="请输入角色名称"
        />
      </el-form-item>
      <el-form-item label="角色标识:" prop="code">
        <el-input
          clearable
          :maxlength="20"
          v-model="form.code"
          placeholder="请输入角色标识"
        />
      </el-form-item>
      <el-form-item label="备注:">
        <el-input
          :rows="4"
          type="textarea"
          :maxlength="200"
          v-model="form.note"
          placeholder="请输入备注"
        />
      </el-form-item>
    </el-form>
    <div slot="footer">
      <el-button @click="updateVisible(false)">取消</el-button>
      <el-button type="primary" @click="save" :loading="loading">
        保存
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
    note: '',
  };

  export default {
    name: 'RoleEdit',
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
              message: '请输入角色名称',
              trigger: 'blur'
            }
          ],
          code: [
            {
              required: true,
              message: '请输入角色标识',
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
          const saveOrUpdate = this.isUpdate ? api.role_update : api.role_create;
          saveOrUpdate(this.form)
            .then((msg) => {
              this.loading = false;
              this.$message.success(msg);
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
            this.$util.assignObject(this.form, this.data);
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
