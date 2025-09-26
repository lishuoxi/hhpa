<template>
  <div class="ele-body">
    <el-card shadow="never">
      <!-- 搜索表单 -->
      <cashflow-search @search="reload" @daochu="daochu"/>
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
      </ele-pro-table>
    </el-card>

  </div>
</template>

<script>
  import CashflowSearch from './components/cashflow-search';
  import * as api from '@/api/admin';

  export default {
    name: 'TradeIndex',
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
            prop: 'cashflow_id',
            label: '单号',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'daifu_amount',
            label: '金额',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'daifu_amount_before',
            label: '操作前余额',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'daifu_amount_after',
            label: '操作后余额',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'created_at',
            label: '创建时间',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 110,
            formatter: (row, column, cellValue) => {
              return this.$util.toDateString(cellValue, 'MM-dd HH:mm:ss');
            }
          },
          {
            prop: 'note',
            label: '备注',
            width: 360,
            align: 'center',
            resizable: false,
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
        return api.cashflow_page({ ...where, ...order, page, limit});
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
      openImport() {
        this.showImport = true;
      },
      /* 刷新表格 */
      daochu(where) {
        const loading = this.$loading({ lock: true });
		  api.cashflow_export({ ...where})
          .then((res) => {
            loading.close();

              var url = res.url;
              window.open(url, '_blank');

            this.$message.success('操作成功');
          })
          .catch((e) => {
            loading.close();
            this.$message.error(e.message);
          });
      },
    }
  };
</script>

<style scoped></style>
