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
      >
        <!-- 凭证 -->
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
              placement="right"
              width="400"
              trigger="click">
              <img :src="row.voucher" width="300px" height="300px"  />
              <img :src="row.voucher" width="30px" height="30px" slot="reference" />
            </el-popover>
        </template>

        <!-- 状态列 -->
        <template slot="status" slot-scope="{ row }">
          <el-tag size="mini" type="info" v-if="row.status=='等待处理'">等待处理</el-tag>
          <el-tag size="mini" type="danger" v-if="row.status=='处理失败'">处理失败</el-tag>
          <el-tag size="mini" type="success" v-if="row.status=='处理成功'">处理成功</el-tag>
        </template>
      </ele-pro-table>
    </el-card>
  </div>
</template>

<script>
  import DaifuSearch from './components/daifu-search';
  import * as api from '@/api/admin';

  export default {
    name: 'AccountOwnerDaifuRealtime',
    components: {
      DaifuSearch
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
            prop: 'daifu_id',
            label: '订单号',
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
            prop: 'account',
            label: '信息',
            showOverflowTooltip: true,
            minWidth: 120,
            slot: 'account',
          },
          {
            prop: 'voucher',
            label: '凭证',
            showOverflowTooltip: true,
            minWidth: 60,
            slot: 'voucher',
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
        return api.daifu_page({ ...where, ...order, page, limit});
      },
      /* 刷新表格 */
      reload(where) {
        this.$refs.table.reload({ page: 1, where: where });
      },
    }
  };
</script>

<style scoped></style>
