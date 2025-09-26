<template>
  <div class="ele-body">
    <el-card shadow="never">
      <!-- 搜索表单 -->
      <role-search @search="reload" />
      <!-- 数据表格 -->
      <ele-pro-table
        ref="table"
        :columns="columns"
        :datasource="datasource"
        :selection.sync="selection"
      >
        <!-- 表头工具栏 -->
        <template slot="toolbar">
          <el-button
            size="small"
            type="primary"
            icon="el-icon-plus"
            class="ele-btn-icon"
            @click="openEdit()"
          >
            添加
          </el-button>
          <el-button
            size="small"
            type="danger"
            icon="el-icon-delete"
            class="ele-btn-icon"
            @click="removeBatch"
          >
            删除
          </el-button>
        </template>
        <!-- 操作列 -->
        <template slot="action" slot-scope="{ row }">
          <el-link
            type="primary"
            :underline="false"
            icon="el-icon-edit"
            @click="openEdit(row)"
          >
            修改
          </el-link>
		<!--
          <el-link
            type="primary"
            :underline="false"
            icon="el-icon-finished"
            @click="openAuth(row)"
          >
            分配权限
          </el-link>
 -->
          <el-popconfirm
            class="ele-action"
            title="确定要删除此角色吗？"
            @confirm="remove(row)"
          >
            <el-link
              type="danger"
              slot="reference"
              :underline="false"
              icon="el-icon-delete"
            >
              删除
            </el-link>
          </el-popconfirm>
        </template>
      </ele-pro-table>
    </el-card>
    <!-- 编辑弹窗 -->
    <role-edit :data="current" :visible.sync="showEdit" @done="reload" />
    <!-- 权限分配弹窗 -->
    <role-auth :data="current" :visible.sync="showAuth" />
  </div>
</template>

<script>
  import RoleSearch from './components/role-search';
  import RoleEdit from './components/role-edit';
  import RoleAuth from './components/role-auth';
  import * as api from '@/api/admin';

  export default {
    name: 'SystemRole',
    components: {
      RoleSearch,
      RoleEdit,
      RoleAuth
    },
    data() {
      return {
        // 表格列配置
        columns: [
          {
            columnKey: 'selection',
            type: 'selection',
            width: 45,
            align: 'center'
          },
          {
            columnKey: 'index',
            type: 'index',
            width: 45,
            align: 'center',
            showOverflowTooltip: true
          },
          {
            prop: 'name',
            label: '角色名称',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'code',
            label: '角色标识',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'note',
            label: '备注',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            columnKey: 'action',
            label: '操作',
            width: 230,
            align: 'center',
            resizable: false,
            slot: 'action'
          }
        ],
        // 表格选中数据
        selection: [],
        // 当前编辑数据
        current: null,
        // 是否显示编辑弹窗
        showEdit: false,
        // 是否显示导入弹窗
        showAuth: false
      };
    },
    methods: {
      /* 表格数据源 */
      datasource({ page, limit, where, order }) {
        return api.role_page({ ...where, ...order, page, limit });
      },
      /* 刷新表格 */
      reload(where) {
        this.$refs.table.reload({ page: 1, where: where });
      },
      /* 显示编辑 */
      openEdit(row) {
        this.current = row;
        this.showEdit = true;
      },
      /* 显示分配权限 */
      openAuth(row) {
        this.current = row;
        this.showAuth = true;
      },
      /* 删除 */
      remove(row) {
        const loading = this.$loading({ lock: true });
		  api.role_remove({id:row.id})
          .then((msg) => {
            loading.close();
            this.$message.success(msg);
            this.reload();
          })
          .catch((e) => {
            loading.close();
            this.$message.error(e.message);
          });
      },
      /* 批量删除 */
      removeBatch() {
        if (!this.selection.length) {
          this.$message.error('请至少选择一条数据');
          return;
        }
        this.$confirm('确定要删除选中的角色吗?', '提示', {
          type: 'warning'
        })
          .then(() => {
            const loading = this.$loading({ lock: true });
			  api.role_remove_batch({ids:this.selection.map((d) => d.id)})
              .then(() => {
                loading.close();
                this.$message.success('操作成功');
                this.reload();
              })
              .catch((e) => {
                loading.close();
                this.$message.error(e.message);
              });
          })
          .catch(() => {});
      }
    }
  };
</script>

<style scoped></style>
