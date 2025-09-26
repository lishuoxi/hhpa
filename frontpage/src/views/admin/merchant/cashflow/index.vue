<template>
  <div class="ele-body">
    <el-card shadow="never">
      <!-- 搜索表单 -->
      <cashflow-search @search="reload"  @daochu="daochu"/>
      <!-- 数据表格 -->
      <ele-pro-table
        ref="table"
        :columns="columns"
        :datasource="datasource"
        :selection.sync="selection"
      >
        <!-- 表头工具栏 -->
        <template slot="toolbar">

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
            {{row.id}}
        </template>
      </ele-pro-table>
    </el-card>
  </div>
</template>

<script>
  import CashflowSearch from './components/cashflow-search';
  import * as api from '@/api/admin';

  export default {
    name: 'MerchantCashflow',
    components: {
      CashflowSearch
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
            label: '用户',
            showOverflowTooltip: true,
            minWidth: 60
          },
          {
            prop: 'amount',
            label: '金额',
            showOverflowTooltip: true,
            minWidth: 60
          },
          {
            prop: 'amount_before',
            label: '变动前',
            showOverflowTooltip: true,
            minWidth: 60
          },
          {
            prop: 'amount_after',
            label: '变动后',
            showOverflowTooltip: true,
            minWidth: 60
          },
          {
            prop: 'note',
            label: '备注',
            showOverflowTooltip: true,
            minWidth: 60
          },
          {
            prop: 'created_at',
            label: '创建时间',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 110,
            formatter: (row, column, cellValue) => {
              return this.$util.toDateString(cellValue);
            }
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
        showImport: false
      };
    },
    methods: {
      /* 表格数据源 */
      datasource({ page, limit, where, order }) {
        return api.cashflow_page({ ...where, ...order, page, limit, role_id:this.role_id });
      },
      /* 刷新表格 */
      reload(where) {
        this.$refs.table.reload({ page: 1, where: where });
      },
      /* 刷新表格 */
      daochu(where) {
        return api.cashflow_export({ ...where });
      },
      /* 打开编辑弹窗 */
      openEdit(row) {
        this.current = row;
        this.showEdit = true;
      },
      /* 打开导入弹窗 */
      openImport() {
        this.showImport = true;
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
