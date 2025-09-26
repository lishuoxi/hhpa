<!-- 编辑弹窗 -->
<template>
  <el-dialog
    width="720px"
    :visible="visible"
    :close-on-click-modal="false"
    custom-class="ele-dialog-form"
    :title="isUpdate ? '修改菜单' : '添加菜单'"
    @update:visible="updateVisible"
  >
    <el-form ref="form" :model="form" :rules="rules" label-width="92px">
      <el-row :gutter="15">
        <el-col :sm="12">
          <el-form-item label="上级菜单:">
            <ele-tree-select
              clearable
              v-model="form.parentId"
              :data="menuList"
              label-key="title"
              value-key="menuId"
              default-expand-all
              placeholder="请选择上级菜单"
            />
          </el-form-item>
          <el-form-item label="菜单名称:" prop="title">
            <el-input
              clearable
              v-model="form.title"
              placeholder="请输入菜单名称"
            />
          </el-form-item>
        </el-col>
        <el-col :sm="12">
          <el-form-item label="菜单类型:">
            <el-radio-group v-model="form.menuType" @change="onMenuTypeChange">
              <el-radio :label="0">菜单</el-radio>
              <el-radio :label="1">按钮</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="打开方式:">
            <el-radio-group
              v-model="form.openType"
              :disabled="form.menuType === 1"
              @change="onOpenTypeChange"
            >
              <el-radio :label="0">组件</el-radio>
              <el-radio :label="1">内链</el-radio>
              <el-radio :label="2">外链</el-radio>
            </el-radio-group>
          </el-form-item>
        </el-col>
      </el-row>
      <div style="margin: 6px 0 28px 0">
        <el-divider />
      </div>
      <el-row :gutter="15">
        <el-col :sm="12">
          <el-form-item label="菜单图标:">
            <ele-icon-picker
              v-model="form.icon"
              placeholder="请选择菜单图标"
              :disabled="form.menuType === 1"
            />
          </el-form-item>
          <el-form-item name="path">
            <template slot="label">
              <el-tooltip
                v-if="form.openType === 2"
                placement="top"
                content="需要以`http://`、`https://`、`//`开头"
              >
                <i class="el-icon-_question"></i>
              </el-tooltip>
              <span>
                {{ form.openType === 2 ? ' 外链地址:' : ' 路由地址:' }}
              </span>
            </template>
            <el-input
              clearable
              v-model="form.path"
              :disabled="form.menuType === 1"
              :placeholder="pathPlaceholder"
            />
          </el-form-item>
          <el-form-item name="component">
            <template slot="label">
              <el-tooltip
                v-if="form.openType === 1"
                placement="top"
                content="需要以`http://`、`https://`、`//`开头"
              >
                <i class="el-icon-_question"></i>
              </el-tooltip>
              <span>
                {{ form.openType === 1 ? ' 内链地址:' : ' 组件路径:' }}
              </span>
            </template>
            <el-input
              clearable
              v-model="form.component"
              :disabled="form.menuType === 1 || form.openType === 2"
              :placeholder="componentPlaceholder"
            />
          </el-form-item>
        </el-col>
        <el-col :sm="12">
          <el-form-item label="权限标识:">
            <el-input
              clearable
              v-model="form.authority"
              placeholder="请输入权限标识"
              :disabled="form.menuType === 0"
            />
          </el-form-item>
          <el-form-item label="排序号:" prop="sortNumber">
            <el-input-number
              :min="0"
              v-model="form.sortNumber"
              placeholder="请输入排序号"
              controls-position="right"
              class="ele-fluid ele-text-left"
            />
          </el-form-item>
          <el-form-item label="是否可见:">
            <el-switch
              :active-value="0"
              :inactive-value="1"
              v-model="form.hide"
              :disabled="form.menuType === 1"
            />
            <el-tooltip
              placement="top"
              content="选择不可见只注册路由不显示在侧边栏，比如添加页面应该选择不可见"
            >
              <i
                class="el-icon-_question"
                style="vertical-align: middle; margin-left: 8px"
              ></i>
            </el-tooltip>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <div slot="footer">
      <el-button @click="updateVisible(false)">取消</el-button>
      <el-button type="primary" :loading="loading" @click="save">
        保存
      </el-button>
    </div>
  </el-dialog>
</template>

<script>
  import EleIconPicker from 'ele-admin/packages/ele-icon-picker';
  import * as api from '@/api/admin';
  const DEFAULT_FORM = {
    menuId: null,
    parentId: '',
    title: '',
    menuType: 0,
    openType: 0,
    icon: '',
    path: '',
    component: '',
    authority: '',
    sortNumber: null,
    hide: 0
  };

  export default {
    name: 'MenuEdit',
    components: { EleIconPicker },
    props: {
      // 弹窗是否打开
      visible: Boolean,
      // 修改回显的数据
      data: Object,
      // 上级菜单id
      parentId: Number,
      // 全部菜单数据
      menuList: Array
    },
    data() {
      return {
        // 表单数据
        form: { ...DEFAULT_FORM },
        // 表单验证规则
        rules: {
          title: [
            {
              required: true,
              message: '请输入菜单名称',
              trigger: 'blur'
            }
          ],
          sortNumber: [
            {
              required: true,
              message: '请输入排序号',
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
    computed: {
      pathPlaceholder() {
        return this.form.openType === 2 ? '请输入外链地址' : '请输入路由地址';
      },
      componentPlaceholder() {
        return this.form.openType === 1 ? '请输入内链地址' : '请输入组件路径';
      }
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
            parentId: this.form.parentId || 0
          };
          const saveOrUpdate = this.isUpdate ? api.menu_update : api.menu_create;
          saveOrUpdate(data)
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
      },
      /* menuType选择改变 */
      onMenuTypeChange() {
        if (this.form.menuType === 0) {
          this.form.authority = null;
        } else {
          this.form.openType = 0;
          this.form.icon = null;
          this.form.path = null;
          this.form.component = null;
          this.form.hide = 0;
        }
      },
      /* openType选择改变 */
      onOpenTypeChange() {
        if (this.form.openType === 2) {
          this.form.component = null;
        }
      },
      /* 判断是否是外链 */
      isUrl(url) {
        return !!(
          url &&
          (url.startsWith('http://') ||
            url.startsWith('https://') ||
            url.startsWith('//'))
        );
      }
    },
    watch: {
      visible(visible) {
        if (visible) {
          if (this.data) {
            const isInnerLink = this.isUrl(this.data.component) ? 1 : 0;
            this.$util.assignObject(this.form, {
              ...this.data,
              openType: this.isUrl(this.data.path) ? 2 : isInnerLink,
              parentId: this.data.parentId === 0 ? '' : this.data.parentId ?? ''
            });
            this.isUpdate = true;
          } else {
            this.form.parentId = this.parentId ?? '';
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
