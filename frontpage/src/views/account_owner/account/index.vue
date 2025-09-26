<template>
  <div class="ele-body">
    <el-card shadow="never">
      <!-- 搜索表单 -->
      <account-search @search="reload" />
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
        </template>
        <template slot="account_type" slot-scope="{ row }">
            {{row.account_type.name}}
        </template>
        <!-- 状态列 -->
        <template slot="status" slot-scope="{ row }">
          <el-switch
            active-value="开启"
            inactive-value="关闭"
            v-model="row.status"
            @change="editStatus(row)"
          />
        </template>
        <!-- 操作列 -->
        <template slot="action" slot-scope="{ row }">

          <el-button
            type="primary"
            :underline="false"
            icon="el-icon-edit"
            @click="openEdit(row)"
            size="mini"
          >
            修改
          </el-button>
          <el-popconfirm
            class="ele-action"
            title="确定要删除此支付码吗？"
            @confirm="remove(row)"
          >
          </el-popconfirm>
        </template>
      </ele-pro-table>
    </el-card>
    <!-- 编辑弹窗 -->
    <account-edit :visible.sync="showEdit" :data="current" @done="reload" />
  </div>
</template>

<script>
  import AccountSearch from './components/account-search';
  import AccountEdit from './components/account-edit';

  import * as api from '@/api/admin';

  export default {
    name: 'AdminAccountAccount',
    components: {
      AccountSearch,
      AccountEdit
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
            prop: 'name',
            label: '名称',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 60
          },
          {
            prop: 'account_type_id',
            label: '类型',
            showOverflowTooltip: true,
            minWidth: 60,
            slot:"account_type"
          },
          {
            prop: 'created_at',
            label: '创建时间',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 80,
            formatter: (row, column, cellValue) => {
              return this.$util.toDateString(cellValue, 'MM-dd HH:mm:ss');
            }
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
            width: 300,
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
        showTest: false
      };
    },
    methods: {
      /* 表格数据源 */
      datasource({ page, limit, where, order }) {
        return api.account_page({ ...where, ...order, page, limit });
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
      /* 打开导入弹窗 */
      openTest(row) {
        this.current = row;
        this.showTest = true;
      },
      /* 删除 */
      remove(row) {
        const loading = this.$loading({ lock: true });
        api.account_remove({id:row.id})
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
        this.$confirm('确定要删除选中的支付码吗?', '提示', {
          type: 'warning'
        })
          .then(() => {
            const loading = this.$loading({ lock: true });
            api.account_remove_batch({ids:this.selection.map((d) => d.id)})
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
      /* 更改状态 */
      editStatus(row) {
        const loading = this.$loading({ lock: true });
        api.account_update_status({id:row.id, status:row.status})
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
