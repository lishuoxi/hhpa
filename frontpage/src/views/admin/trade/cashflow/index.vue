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
      >
        <!-- 表头工具栏 -->
        <template slot="toolbar">
        </template>

        <template slot="username" slot-scope="{ row }">
            <span style="font-size: 12px">
                {{row.user?.username}} 
            </span>
        </template>

        <template slot="amount" slot-scope="{ row }">
            <span style="color: blue">
                {{row.amount}} 
            </span>
        </template>

        <template slot="daifu_amount" slot-scope="{ row }">
            <span style="color: blue">
                {{row.daifu_amount}} 
            </span>
        </template>
        <!-- 操作列 -->
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
            columnKey: 'id',
            type: 'index',
            width: 45,
            align: 'center',
            showOverflowTooltip: true
          },
          {
            prop: 'user_id',
            label: '用户名',
            showOverflowTooltip: true,
            minWidth: 110,
            slot: 'username'
          },
          {
            prop: 'amount_before',
            label: '变动前',
            showOverflowTooltip: true,
            minWidth: 110,
          },
          {
            prop: 'amount_after',
            label: '变动后',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'amount',
            label: '变动金额',
            showOverflowTooltip: true,
            minWidth: 110,
            slot: 'amount',
          },
          {
            prop: 'daifu_amount_before',
            label: '代付变动前',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'daifu_amount_after',
            label: '代付变动后',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'daifu_amount',
            label: '代付变动金额',
            showOverflowTooltip: true,
            minWidth: 110,
            slot: 'daifu_amount'
          },
          {
            prop: 'created_at',
            label: '创建时间',
            showOverflowTooltip: true,
            minWidth: 120,
            formatter: (row, column, cellValue) => {
              return this.$util.toDateString(cellValue);
            }
          },
          {
            prop: 'note',
            label: '备注',
            align: 'center',
            width: 180,
            resizable: false,
          },
        ],
        // 当前编辑数据
        current: null,
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
