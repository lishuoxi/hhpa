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

        <!-- 凭证 -->
        <template slot="voucher" slot-scope="{ row }">
            <el-popover
              placement="right"
              width="400"
              trigger="click">
              <img :src="row.voucher" width="150px" height="150px"  />
              <img :src="row.voucher" width="30px" height="30px" slot="reference" />
            </el-popover>
        </template>


        <template slot="status" slot-scope="{ row }">
          <el-tag size="mini" type="info" v-if="row.status=='等待反查'">等待反查</el-tag>
          <el-tag size="mini" type="primary" v-if="row.status=='反查成功'">反查成功</el-tag>
          <el-tag size="mini" type="danger" v-if="row.status=='反查失败'">反查失败</el-tag>
          <el-tag size="mini" type="danger" v-if="row.status=='处理失败'">处理失败</el-tag>
          <el-tag size="mini" type="success" v-if="row.status=='处理成功'">处理成功</el-tag>
        </template>

      </ele-pro-table>
    </el-card>
    <daifu-edit :visible.sync="showEdit"  @done="reload" />
  </div>
</template>

<script>
  import DaifuSearch from './components/daifu-search';
  import DaifuEdit from './components/daifu-edit';
  import * as api from '@/api/admin';

  export default {
    name: 'MerchantDaifuTrade',
    components: {
      DaifuSearch,
      DaifuEdit
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
            minWidth: 60
          },
          {
            prop: 'out_daifu_id',
            label: '商户订单号',
            minWidth: 60
          },
          {
            prop: 'amount',
            label: '金额',
            showOverflowTooltip: true,
            minWidth: 60
          },
          {
            prop: 'voucher',
            label: '凭证',
            showOverflowTooltip: true,
            minWidth: 50,
              slot: 'voucher'
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
            prop: 'status',
            label: '状态',
            align: 'center',
            width: 100,
            resizable: false,
            slot: 'status'
          }
        ],
        // 表格选中数据
        selection: [],
        showEdit: false,
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
      /* 打开编辑弹窗 */
      openEdit() {
        this.showEdit = true;
      },
    }
  };
</script>

<style scoped></style>
