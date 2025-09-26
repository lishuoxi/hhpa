<!-- 角色权限分配弹窗 -->
<template>
  <el-dialog
    width="460px"
    title="分配权限"
    :visible="visible"
    :close-on-click-modal="false"
    @update:visible="updateVisible"
  >
    <el-scrollbar
      v-loading="authLoading"
      style="height: 60vh"
      wrap-style="overflow-x: hidden;"
    >
      <el-tree
        ref="tree"
        show-checkbox
        :data="authData"
        node-key="menuId"
        :default-expand-all="true"
        :props="{ label: 'title' }"
        :default-checked-keys="checkedKeys"
      >
        <span slot-scope="{ data }">
          <i :class="data.icon"></i>
          <span> {{ data.title }}</span>
        </span>
      </el-tree>
    </el-scrollbar>
    <div slot="footer">
      <el-button @click="updateVisible(false)">取消</el-button>
      <el-button type="primary" :loading="loading" @click="save">
        保存
      </el-button>
    </div>
  </el-dialog>
</template>

<script>
  import * as api from '@/api/admin';

  export default {
    name: 'RoleAuth',
    props: {
      // 弹窗是否打开
      visible: Boolean,
      // 当前角色数据
      data: Object
    },
    data() {
      return {
        // 权限数据
        authData: [],
        // 权限数据请求状态
        authLoading: false,
        // 提交状态
        loading: false,
        // 角色权限选中的keys
        checkedKeys: []
      };
    },
    methods: {
      /* 查询权限数据 */
      query() {
        this.authData = [];
        this.checkedKeys = [];
        if (!this.data) {
          return;
        }
        this.authLoading = true;
        api.role_menus_lists(this.data.roleId)
          .then((data) => {
            this.authLoading = false;
            // 转成树形结构的数据
            this.authData = this.$util.toTreeData({
              data: data,
              idKey: 'menuId',
              pidKey: 'parentId'
            });
            // 回显选中的数据
            const cks = [];
            this.$util.eachTreeData(this.authData, (d) => {
              if (d.checked && !d.children?.length) {
                cks.push(d.menuId);
              }
            });
            this.checkedKeys = cks;
          })
          .catch((e) => {
            this.authLoading = false;
            this.$message.error(e.message);
          });
      },
      /* 保存权限分配 */
      save() {
        this.loading = true;
        const ids = this.$refs.tree
          .getCheckedKeys()
          .concat(this.$refs.tree.getHalfCheckedKeys());
        api.role_menus_update(this.data.roleId, ids)
          .then((msg) => {
            this.loading = false;
            this.$message.success(msg);
            this.updateVisible(false);
          })
          .catch((e) => {
            this.loading = false;
            this.$message.error(e.message);
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
          this.query();
        }
      }
    }
  };
</script>

<style scoped></style>
