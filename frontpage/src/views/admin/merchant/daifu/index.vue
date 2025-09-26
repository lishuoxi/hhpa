<template>
  <div class="ele-body">
    <el-card shadow="never">
      <!-- 搜索表单 -->
      <daifu-search @search="reload" />
      <!-- 数据表格 -->
      <ele-pro-table
        ref="table"
        :columns="columns"
        :datasource="datasource"
        :selection.sync="selection"
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

        <template slot="daifu_id" slot-scope="{ row }">
            {{row.daifu_id}} <br/>
            {{row.out_daifu_id}}
        </template>

        <template slot="merchant" slot-scope="{ row }">
            {{row.merchant?.username}}
        </template>

        <template slot="account_owner" slot-scope="{ row }">
            {{row.account_owner?.username}}
        </template>

        <template slot="created_at" slot-scope="{ row }">
            {{ formatDate(row.created_at) }} <br/>
            {{ formatDate(row.success_at) }} 
        </template>

        <template slot="account" slot-scope="{ row }">
            {{row.account_name}}
            <el-tag size="mini">
                {{row.bank}}
            </el-tag>
            <br/>
            {{row.account}}
        </template>
        <!-- 凭证 -->
        <template slot="voucher" slot-scope="{ row }">
            <el-popover
                    v-if="row.voucher != ''"
              placement="right"
              width="400"
              trigger="click">
              <img :src="row.voucher" width="300px" height="300px"  />
              <img :src="row.voucher" width="30px" height="30px" slot="reference" />
            </el-popover>
        </template>

        <!-- 状态列 -->
        <template slot="status" slot-scope="{ row }">
          <el-tag size="mini" type="info" v-if="row.receive_status=='待接单'">待接单</el-tag>
          <el-tag size="mini" type="primary" v-if="row.receive_status=='待提交'">待提交</el-tag>
          <el-tag size="mini" type="success" v-if="row.receive_status=='已提交'">已提交</el-tag>
          <br/>
          <el-tag size="mini" type="info" v-if="row.status=='等待反查'">等待反查</el-tag>
          <el-tag size="mini" type="primary" v-if="row.status=='反查成功'">反查成功</el-tag>
          <el-tag size="mini" type="danger" v-if="row.status=='反查失败'">反查失败</el-tag>
          <el-tag size="mini" type="danger" v-if="row.status=='处理失败'">处理失败</el-tag>
          <el-tag size="mini" type="success" v-if="row.status=='处理成功'">处理成功</el-tag>
        </template>
        <!-- 操作列 -->
        <template slot="action" slot-scope="{ row }">
          <el-button 
            v-if="row.status == '反查成功'"
            size="mini"
            type="success"
            :underline="false"
            icon="el-icon-edit"
            @click="make_success(row)"
          >
            处理完成
          </el-button>
              <br/>
          <el-button 
            v-if="row.status == '反查成功'"
            size="mini"
            type="danger"
            :underline="false"
            icon="el-icon-close"
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
  import DaifuSearch from './components/daifu-search';
  import * as api from '@/api/admin';

  export default {
    name: 'MerchantDaifu',
    components: {
      DaifuSearch
    },
    data() {
      return {
        // 表格列配置
        columns: [
          /*{
            columnKey: 'selection',
            type: 'selection',
            width: 45,
            align: 'center'
          },
          */
          {
            prop: 'id',
            width: 45,
            align: 'center',
            showOverflowTooltip: true
          },
          {
            prop: 'daifu_id',
            label: '订单号/商户订单号',
            showOverflowTooltip: true,
            minWidth: 90,
              slot: 'daifu_id'
          },
          {
            prop: 'merchant_id',
            label: '商户',
            showOverflowTooltip: true,
            minWidth: 40,
              slot: 'merchant'
          },
          {
            prop: 'account_owner_id',
            label: '码商',
            showOverflowTooltip: true,
            minWidth: 60,
              slot: 'account_owner'
          },
          {
            prop: 'amount',
            label: '金额',
            showOverflowTooltip: true,
            minWidth: 50
          },
          {
            prop: 'account',
            label: '账号',
            showOverflowTooltip: true,
            minWidth: 120,
              slot: 'account'
          },
          {
            prop: 'voucher',
            label: '凭证',
            showOverflowTooltip: true,
            minWidth: 30,
              slot: 'voucher'
          },
          {
            prop: 'created_at',
            label: '创建时间',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 60,
              slot: 'created_at'
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
            width: 130,
            align: 'center',
            resizable: false,
            slot: 'action'
          }
        ],
        // 表格选中数据
        selection: [],
        // 当前编辑数据
        current: null,
          total_amount: 0,
          total_count: 0,
          success_amount: 0,
          success_count: 0,
      };
    },
    methods: {
      /* 表格数据源 */
      datasource({ page, limit, where, order }) {
        return api.daifu_page({ ...where, ...order, page, limit});
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
      make_success(row) {
        const loading = this.$loading({ lock: true });
        api.daifu_update_status({id:row.id, status:'处理成功'})
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
       formatDate(date)
        {
            return this.$util.toDateString(date, 'MM-dd HH:mm:ss');
        },

      /* 更改状态 */
      make_fail(row) {
        const loading = this.$loading({ lock: true });
        api.daifu_update_status({id:row.id, status:'处理失败'})
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
