<template>
  <div class="ele-body">
    <el-card shadow="never">
      <!-- 搜索表单 -->
      <recharge-search @search="reload" />
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

        <template slot="receipts" slot-scope="{ row }">
            <el-popover
              placement="right"
              width="400"
              trigger="click">
              <img :src="row.receipts" width="150px" height="150px"  />

              <img :src="row.receipts" width="30px" height="30px" slot="reference" />
            </el-popover>
        </template>

        <template slot="status" slot-scope="{ row }">
          <el-tag type="info" v-if="row.status=='等待处理'" size="mini">等待处理</el-tag>
          <el-tag type="success" v-if="row.status=='处理成功'" size="mini">处理成功</el-tag>
          <el-tag type="danger" v-if="row.status=='处理失败'" size="mini">处理失败</el-tag>
        </template>
      </ele-pro-table>
    </el-card>
    <!-- 编辑弹窗 -->
    <recharge-edit :visible.sync="showEdit" :data="current" @done="reload" />
  </div>
</template>

<script>
  import RechargeSearch from './components/recharge-search';
  import RechargeEdit from './components/recharge-edit';
  import * as api from '@/api/admin';

  export default {
    name: 'AccountOwnerRecharge',
    components: {
      RechargeSearch,
      RechargeEdit
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
            prop: 'recharge_id',
            label: '充值单号',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'amount',
            label: '金额',
            showOverflowTooltip: true,
            minWidth: 60
          },
          {
            prop: 'receipts',
            label: '凭证',
            showOverflowTooltip: true,
            minWidth: 110,
            slot: "receipts"
          },
          {
              prop: 'status',
              label: '状态',
              showOverflowTooltip: true,
              minWidth: 110,
              slot: 'status'
          },
          {
            prop: 'note',
            label: '备注',
            showOverflowTooltip: true,
            minWidth: 110,
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
            prop: 'success_at',
            label: '到账时间',
            showOverflowTooltip: true,
            minWidth: 110,
            formatter: (row, column, cellValue) => {
              return this.$util.toDateString(cellValue, 'MM-dd HH:mm:ss');
            }
          }
        ],
        // 表格选中数据
        selection: [],
        // 当前编辑数据
        current: null,
        // 是否显示编辑弹窗
        showEdit: false,
      };
    },
    methods: {
      /* 表格数据源 */
      datasource({ page, limit, where, order }) {
        return api.recharge_page({ ...where, ...order, page, limit});
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
    }
  };
</script>

<style scoped></style>
