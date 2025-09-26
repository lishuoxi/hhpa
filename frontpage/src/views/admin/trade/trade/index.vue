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
                    成功{{success_count}}笔{{success_amount}}元,成功率{{(100.0*success_count/total_count).toFixed(2)}}%
                </span>
            </div>
        </template>
        <!-- 表头工具栏 -->
        <template slot="toolkit">
            <div class="list-tool-item">
                <span>还有{{refresh_interval-last_refresh_time}}秒自动刷新</span>
            </div>
            <div class="list-tool-divider">
              <el-divider direction="vertical" />
            </div>
            <div class="list-tool-item">
                <span>刷新频率</span>
            </div>
            <div class="list-tool-item">
                <el-input size="mini" class="el-input" v-model="refresh_interval" placeholder="请输入" style="width:50px;"/>
            </div>

            <div class="list-tool-divider">
              <el-divider direction="vertical" />
            </div>

            <div class="list-tool-item">
              <el-switch 
                 v-model="is_refresh" 
                 size="mini"  
                 active-text="自动刷新" /> 
            </div>

            <div class="list-tool-divider">
              <el-divider direction="vertical" />
            </div>
        </template>

        <template slot="trade_id" slot-scope="{ row }">
            <span style="font-size: 12px">
                {{row.trade_id}} 
            </span>
        </template>

        <template slot="out_trade_id" slot-scope="{ row }">
            <span style="font-size: 12px">
                {{row.out_trade_id}}
            </span>
        </template>

        <template slot="channel" slot-scope="{ row }">
            <span style="font-size: 12px">
                {{row.channel?.name}} 
            </span>
        </template>

        <!-- 状态列 -->
        <template slot="status" slot-scope="{ row }">
          <el-tag type="info" v-if="row.status=='等待支付'" size="mini">待支付</el-tag>
          <el-tag type="success" v-if="row.status=='支付完成'" size="mini">成功</el-tag>
          <el-tag type="danger" v-if="row.status=='支付失败'" size="mini">失败</el-tag>
          <el-tag type="info" v-if="row.notify_status=='等待通知'" size="mini">待通知</el-tag>
          <el-tag type="success" v-if="row.notify_status=='通知成功'" size="mini">成功</el-tag>
          <el-tag type="danger" v-if="row.notify_status=='通知失败'" size="mini">失败</el-tag>
        </template>
        <!-- 操作列 -->
        <template slot="action" slot-scope="{ row }">
          <el-popconfirm
                  size="mini"
            class="ele-action"
            title="确定要手动确认订单吗？"
            @confirm="confirmTrade(row)"
            v-if="row.status!='支付完成' || row.notify_status!='通知成功'"
          >
              <el-button
                size="mini"
                type="primary"
                icon="el-icon-plus"
                class="ele-btn-icon"
                slot="reference"
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
    name: 'AdminTradeIndex',
    components: {
      TradeSearch
    },
    data() {
      return {
        // 表格列配置
        columns: [
          {
            prop: 'id',
            width: 45,
            align: 'center',
            showOverflowTooltip: true
          },
          {
            prop: 'trade_id',
            label: '订单号',
            showOverflowTooltip: true,
            minWidth: 110,
            slot: 'trade_id'
          },
          {
            prop: 'out_trade_id',
            label: '商户订单号',
            showOverflowTooltip: true,
            minWidth: 100,
            slot: 'out_trade_id'
          },
          {
            prop: 'merchant_id',
            label: '商户',
            showOverflowTooltip: true,
            minWidth: 80,
            formatter: (row) => {
              return row.merchant?.username;
            }
          },
          {
            prop: 'amount',
            label: '金额/实付',
            showOverflowTooltip: true,
            minWidth: 120,
            formatter: (row) => {
              return  parseFloat(row.amount).toFixed(2) + "/" + parseFloat(row.amount_real).toFixed(2);
            }
          },
          {
            prop: 'channel_id',
            label: '通道',
            showOverflowTooltip: true,
            minWidth: 80,
            slot: 'channel'
          },
          {
            prop: 'account_id',
            label: '付款码',
            showOverflowTooltip: true,
            minWidth: 80,
            formatter: (row) => {
              return row.account?.name;
            }
          },
          {
            prop: 'payer',
            label: '付款人姓名',
            minWidth: 60,
          },
          {
            prop: 'account_owner.id',
            label: '码商',
            showOverflowTooltip: true,
            minWidth: 80,
            formatter: (row) => {
              return row.account_owner?.username;
            }
          },
          {
            prop: 'merchant_rate',
            label: '费率',
            showOverflowTooltip: true,
            minWidth: 70,
            formatter: (row, column, cellValue) => {
              return parseFloat(cellValue).toFixed(2) + "%";
            }
          },
          {
            prop: 'created_at',
            label: '创建时间',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 120,
            formatter: (row, column, cellValue) => {
              return this.$util.toDateString(cellValue, 'MM-dd HH:mm:ss');
            }
          },
            /*
          {
            prop: 'success_at',
            label: '成功时间',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 120,
            formatter: (row, column, cellValue) => {
              return this.$util.toDateString(cellValue, 'MM-dd HH:mm:ss');
            }
          },
          */
          {
            prop: 'status',
            label: '状态',
            align: 'center',
            width: 140,
            resizable: false,
            slot: 'status'
          },
          {
            columnKey: 'action',
            label: '操作',
            width: 90,
            align: 'center',
            resizable: false,
            slot: 'action'
          }
        ],
        // 当前编辑数据
        current: null,
        role_id: 1,
        refresh_interval: 10,
        last_refresh_time: 0,
        is_refresh: true,
        interval_id: '',
          total_amount: 0,
          total_count: 0,
          success_amount: 0,
          success_count: 0,
      };
    },
      created() {
          this.interval_id = setInterval(this.time_refresh, 1000);
      },
      beforeDestroy() {
          if (this.interval_id) {
              clearInterval(this.interval_id);
          }
      },
    methods: {
        time_refresh(){
            if(!this.is_refresh){
                return;
            }
            if(this.last_refresh_time == 0){
                this.reload();
            }
            this.last_refresh_time = (this.last_refresh_time+1)%this.refresh_interval;
        },
      /* 表格数据源 */
      datasource({ page, limit, where, order }) {
        return api.trade_page({ ...where, ...order, page, limit, role_id:this.role_id });
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
      /* 更改状态 */
      confirmTrade(row) {
        const loading = this.$loading({ lock: true });
		  api.trade_confirm({id:row.id})
          .then(() => {
            loading.close();
            this.reload();
            this.$message.success('操作成功');
          })
          .catch((e) => {
            loading.close();
            this.$message.error(e.message);
          });
      }
    }
  };
</script>
<style lang="scss" scoped>
  .list-tool-item > span {
    vertical-align: middle;
    margin-right: 6px;
  }

  .list-tool-divider {
    padding: 0 12px;
  }
</style>
