<template>
  <div class="ele-body">
    <el-card shadow="never">
      <!-- 搜索表单 -->
      <trade-search @search="reload" />
      <!-- 数据表格 -->
      <ele-pro-table
        ref="table"
        :columns="columns"
        :datasource="datasource"
        :parse-data="parseData"
      >

        <template slot="toolbar">
            <div class="list-tool-item">
                <span>
                    共{{total_count}}笔{{total_amount}}元;
                    成功{{success_count}}笔{{success_amount}}元,成功率{{(1.0*success_count/total_count).toFixed(2)}}%
                </span>
            </div>
        </template>
        <!-- 状态列 -->
        <template slot="status" slot-scope="{ row }">
          <el-tag type="success" v-if="row.status=='通知成功'">通知成功</el-tag>
          <el-tag type="danger" v-if="row.status=='通知失败'">通知失败</el-tag>
        </template>
        <template slot="notify_status" slot-scope="{ row }">
          <el-tag type="success" v-if="row.notify_status=='通知成功'">通知成功</el-tag>
          <el-tag type="info" v-if="row.notify_status=='等待通知'">等待通知</el-tag>
          <el-tag type="danger" v-if="row.notify_status=='通知失败'">通知失败</el-tag>
        </template>
        <!-- 操作列 -->
        <template slot="action" slot-scope="{ row }">
          <el-popconfirm
            class="ele-action"
            title="确定要手动确认订单吗？"
            @confirm="remove(row)"
          >
          <el-button
            size="small"
            type="primary"
            icon="el-icon-plus"
            class="ele-btn-icon"
            @click="openEdit()"
          >
            手动
          </el-button>

          </el-popconfirm>
        </template>
      </ele-pro-table>
    </el-card>

  </div>
</template>

<script>
  import TradeSearch from './components/trade-search';
  import * as api from '@/api/admin';

  export default {
    name: 'TradeIndex',
    components: {
      TradeSearch
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
            prop: 'trade_id',
            label: '订单号',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 110,
          },
          {
            prop: 'out_trade_id',
            label: '商户订单号',
            showOverflowTooltip: true,
            minWidth: 110,
          },
          {
            prop: 'amount',
            label: '金额',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'amount_real',
            label: '实付金额',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'merchant_rate',
            label: '费率',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'created_at',
            label: '创建时间',
            showOverflowTooltip: true,
            minWidth: 120,
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
            prop: 'notify_status',
            label: '通知状态',
            align: 'center',
            sortable: 'custom',
            width: 80,
            resizable: false,
            slot: 'notify_status'
          },
        ],
        total_amount: 0,
        total_count: 0,
        success_amount: 0,
        success_count: 0,
      };
    },
    methods: {
      /* 表格数据源 */
      datasource({ page, limit, where, order }) {
        return api.trade_page({ ...where, ...order, page, limit});
      },
        parseData(res){
            this.total_amount = res.trade_all_amount;
            this.total_count = res.trade_all_count;
            this.success_amount = res.trade_success_amount;
            this.success_count = res.trade_success_count;
            return res;
        },
      /* 刷新表格 */
      reload(where) {
        this.$refs.table.reload({ page: 1, where: where });
      },
    }
  };
</script>

<style scoped></style>
