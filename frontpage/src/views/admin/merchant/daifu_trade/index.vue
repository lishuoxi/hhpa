<template>
  <div class="ele-body">
    <el-card shadow="never">
      <!-- 搜索表单 -->
      <daifu-trade-search @search="reload" />
      <!-- 数据表格 -->
      <ele-pro-table
        ref="table"
        :columns="columns"
        :datasource="datasource"
        :selection.sync="selection"
      >

        <!-- 状态列 -->
        <template slot="merchant" slot-scope="{ row }">
            {{row.merchant?.username}}
        </template>

        <!-- 状态列 -->
        <template slot="status" slot-scope="{ row }">
          <el-tag size="mini" type="info" v-if="row.status=='等待处理'">等待处理</el-tag>
          <el-tag size="mini" type="danger" v-if="row.status=='处理失败'">处理失败</el-tag>
          <el-tag size="mini" type="success" v-if="row.status=='处理成功'">处理成功</el-tag>
        </template>
        <!-- 操作列 -->
        <template slot="action" slot-scope="{ row }">
          <el-button 
            v-if="row.status == '等待处理'"
            size="mini"
            type="success"
            :underline="false"
            icon="el-icon-edit"
            @click="make_success(row)"
          >
            处理完成
          </el-button>
          <el-button 
            v-if="row.status == '等待处理'"
            size="mini"
            type="danger"
            :underline="false"
            icon="el-icon-delete"
            @click="make_fail(row)"
          >
            处理失败
          </el-button>

        </template>
      </ele-pro-table>
    </el-card>
  </div>
</template>

<script>
  import DaifuTradeSearch from './components/daifu-trade-search';
  import * as api from '@/api/admin';

  export default {
    name: 'MerchantDaifuTrade',
    components: {
      DaifuTradeSearch
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
            prop: 'daifu_trade_id',
            label: '订单号',
            showOverflowTooltip: true,
            minWidth: 60
          },
          {
            prop: 'merchant_id',
            label: '商户',
            showOverflowTooltip: true,
            minWidth: 60,
              slot: 'merchant'
          },
          {
            prop: 'amount',
            label: '金额',
            showOverflowTooltip: true,
            minWidth: 60
          },
          {
            prop: 'created_at',
            label: '创建时间',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 60,
            formatter: (row, column, cellValue) => {
              return this.$util.toDateString(cellValue, 'MM-dd HH:mm:ss');
            }
          },
          {
            prop: 'success_at',
            label: '成功时间',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 60,
            formatter: (row, column, cellValue) => {
              return this.$util.toDateString(cellValue, 'MM-dd HH:mm:ss');
            }
          },
          {
            prop: 'status',
            label: '状态',
            align: 'center',
            minWidth: 40,
            resizable: false,
            slot: 'status'
          },
          {
            columnKey: 'action',
            label: '操作',
            width: 290,
            align: 'center',
            resizable: false,
            slot: 'action'
          }
        ],
        // 表格选中数据
        selection: [],
        // 当前编辑数据
        current: null,
      };
    },
    methods: {
      /* 表格数据源 */
      datasource({ page, limit, where, order }) {
        return api.daifu_trade_page({ ...where, ...order, page, limit});
      },
      /* 刷新表格 */
      reload(where) {
        this.$refs.table.reload({ page: 1, where: where });
      },
      /* 更改状态 */
      make_success(row) {
        const loading = this.$loading({ lock: true });
        api.daifu_trade_update_status({id:row.id, status:'处理成功'})
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

      /* 更改状态 */
      make_fail(row) {
        const loading = this.$loading({ lock: true });
        api.daifu_trade_update_status({id:row.id, status:'处理失败'})
          .then(() => {
            loading.close();
            this.$message.success('操作成功');
              this.reload();
          })
          .catch((e) => {
            loading.close();
            this.$message.error(e.message);
          });
      }
    }
  };
</script>

<style scoped></style>
