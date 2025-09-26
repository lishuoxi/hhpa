<template>
  <div class="ele-body">
    <el-card shadow="never">
      <!-- 搜索表单 -->
      <user-search @search="reload" />
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
            新建
          </el-button>
              <!--
          <el-button
            size="small"
            type="danger"
            icon="el-icon-delete"
            class="ele-btn-icon"
            @click="removeBatch"
          >
            删除
          </el-button>
              -->
		<!--
          <el-button
            size="small"
            icon="el-icon-upload2"
            class="ele-btn-icon"
            @click="openImport"
          >
            导入
          </el-button>
		-->
        </template>
        <!-- 状态列 -->
        <template slot="status" slot-scope="{ row }">
          <el-switch
            active-value="启用"
            inactive-value="冻结"
            v-model="row.status"
            @change="editStatus(row)"
          />
        </template>
        <!-- 操作列 -->
        <template slot="action" slot-scope="{ row }">
          <el-button
            size="mini"
            type="primary"
            :underline="false"
            icon="el-icon-edit"
            @click="openEdit(row)"
          >
            修改
          </el-button>
          <el-button
          size="mini"
            type="warning"
            :underline="false"
            icon="el-icon-key"
            @click="resetPsw(row)"
          >
            重置密码
          </el-button> <br />
          <el-popconfirm
            class="ele-action"
            title="确定要删除此用户吗？"
            @confirm="remove(row)"
          >
            <el-button
              size="mini"
              type="danger"
              slot="reference"
              :underline="false"
              icon="el-icon-delete"
            >
              删除
            </el-button>
          </el-popconfirm>
          <el-button
            size="mini"
            type="primary"
            :underline="false"
            icon="el-icon-key"
            @click="resetGoogle(row)"
          >
            重置谷歌
          </el-button>

        </template>
      </ele-pro-table>
    </el-card>
    <!-- 编辑弹窗 -->
    <user-edit :visible.sync="showEdit" :data="current" @done="reload" />
  </div>
</template>

<script>
  import UserSearch from './components/user-search';
  import UserEdit from './components/user-edit';
  import * as api from '@/api/admin';

  export default {
    name: 'SystemAdmin',
    components: {
      UserSearch,
      UserEdit,
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
            columnKey: 'id',
            type: 'index',
            width: 45,
            align: 'center',
            showOverflowTooltip: true
          },
          {
            prop: 'username',
            label: '用户名',
            showOverflowTooltip: true,
            minWidth: 80
          },
          {
            prop: 'realname',
            label: '昵称',
            showOverflowTooltip: true,
            minWidth: 80
          },
          {
            prop: 'created_at',
            label: '创建时间',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 106,
            formatter: (row, column, cellValue) => {
              return this.$util.toDateString(cellValue);
            }
          },
          {
            prop: 'last_login_at',
            label: '上次登陆',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 106
          },
          {
            prop: 'admin_secure_ips',
            label: '登陆安全IP',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'status',
            label: '状态',
            align: 'center',
            width: 80,
            resizable: false,
            slot: 'status'
          },
          {
            columnKey: 'action',
            label: '操作',
            width: 220,
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
        showImport: false,
        role_id: 1,
      };
    },
    methods: {
      /* 表格数据源 */
      datasource({ page, limit, where, order }) {
        return api.user_page({ ...where, ...order, page, limit, role_id:this.role_id });
      },
      /* 刷新表格 */
      reload(where) {
        this.$refs.table.reload({ page: 1, where: where });
      },
      /* 打开编辑弹窗 */
      openEdit(row) {
        this.current = row;
        this.showEdit = true;
      },
      /* 删除 */
      remove(row) {
        const loading = this.$loading({ lock: true });
		  api.user_remove({id:row.id})
          .then(() => {
            loading.close();
			  this.$message.success('操作成功');
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
        this.$confirm('确定要删除选中的用户吗?', '提示', {
          type: 'warning'
        })
          .then(() => {
            const loading = this.$loading({ lock: true });
            api.user_remove_batch({ids:this.selection.map((d) => d.id)})
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
      },
      /* 重置用户密码 */
      resetPsw(row) {
        this.$confirm('确定要重置此用户的密码为"123456"吗?', '提示', {
          type: 'warning'
        })
          .then(() => {
            const loading = this.$loading({ lock: true });
			  api.user_update_password({id:row.id})
              .then(() => {
                loading.close();
                this.$message.success('重置成功');
              })
              .catch((e) => {
                loading.close();
                this.$message.error(e.message);
              });
          })
          .catch(() => {});
      },
      /* 重置用户密码 */
      resetGoogle(row) {
        this.$confirm('确定要重置此用户的谷歌为空吗?', '提示', {
          type: 'warning'
        })
          .then(() => {
            const loading = this.$loading({ lock: true });
          api.user_update_google({id:row.id})
              .then(() => {
                loading.close();
                this.$message.success('重置成功');
              })
              .catch((e) => {
                loading.close();
                this.$message.error(e.message);
              });
          })
          .catch(() => {});
      },
      /* 重置用户密码 */
      resetSecurePwd(row) {
        this.$confirm('确定要重置此用户的密码为"123456"吗?', '提示', {
          type: 'warning'
        })
          .then(() => {
            const loading = this.$loading({ lock: true });
        api.user_update_password({id:row.id})
              .then(() => {
                loading.close();
                this.$message.success('重置成功');
              })
              .catch((e) => {
                loading.close();
                this.$message.error(e.message);
              });
          })
          .catch(() => {});
      },
      /* 更改状态 */
      editStatus(row) {
        const loading = this.$loading({ lock: true });
        api.user_update_status({id:row.id, status:row.status})
          .then(() => {
            loading.close();
            this.$message.success('操作成功');
          })
          .catch((e) => {
            loading.close();
            row.status = row.status=='启用' ? '冻结' : '启用';
            this.$message.error(e.message);
          });
      }
    }
  };
</script>

<style scoped></style>
