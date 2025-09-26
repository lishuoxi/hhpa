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

        <template slot="toolbar">
            <div class="list-tool-item">
              <el-switch 
                 v-model="is_refresh" 
                 size="mini"  
                 active-text="自动刷新" /> 
            </div>
        </template>

        <!-- 状态列 -->
        <template slot="merchant" slot-scope="{ row }">
            {{row.merchant?.username}}
        </template>

        <!-- 凭证 -->
        <template slot="account" slot-scope="{ row }">
            <div v-if="row.receive_status == '待接单'" >
                *** ***
            </div>
            <div v-else >
                {{row.account_name}}
                <el-tag size="mini">
                    {{row.bank}}
                </el-tag>
                <br/>
                {{row.account}}
             </div>
        </template>

        <!-- 状态列 -->
        <template slot="receive_status" slot-scope="{ row }">
            {{row.receive_status}}
        </template>

        <template slot="action" slot-scope="{ row }">
          <el-button
            type="primary"
            :underline="false"
            icon="el-icon-edit"
            @click="jiedan(row)"
            size="mini"
            v-if="row.receive_status == '待接单'"
          >
            接单
          </el-button>
          <el-button
            type="success"
            :underline="false"
            icon="el-icon-edit"
            @click="openEdit(row)"
            size="mini"
            v-if="row.receive_status == '待提交'"
          >
            上传凭证
          </el-button>
        </template>
      </ele-pro-table>
    </el-card>

    <!-- 编辑弹窗 -->
    <daifu-edit :visible.sync="showEdit" :data="current" @done="reload" />
  </div>
</template>

<script>
  import DaifuTradeSearch from './components/daifu-search';
  import DaifuEdit from './components/daifu-edit';
  import * as api from '@/api/admin';

  export default {
    name: 'AccountOwnerDaifuRealtime',
    components: {
      DaifuTradeSearch,
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
            prop: 'id',
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
            prop: 'receive_status',
            label: '状态',
            align: 'center',
            minWidth: 40,
            resizable: false,
            slot: 'receive_status'
          },
          {
            columnKey: 'action',
            label: '操作',
            align: 'center',
            minWidth: 40,
            resizable: false,
            slot: 'action'
          },
        ],
        // 表格选中数据
        selection: [],
        // 是否显示编辑弹窗
        showEdit: false,
        // 当前编辑数据
        current: null,
        is_refresh: false,
          interval_id: null
      };
    },
      created() {
          this.interval_id = setInterval(this.time_refresh, 3000);
      },
      beforeDestroy() {
          console.log('before destroy');
          if (this.interval_id) {
              clearInterval(this.interval_id);
          }
      },
      methods: {
          time_refresh(){
              if(!this.is_refresh){
                  return;
              }
              this.reload();
          },
      /* 表格数据源 */
      datasource({ page, limit, where, order }) {
        return api.daifu_page_realtime({ ...where, ...order, page, limit});
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
      jiedan(row) {
          this.loading = true;
          api.daifu_receive({id: row.id})
            .then(() => {
              this.loading = false;
                this.reload();
              this.$message.success('操作成功');
            })
            .catch((e) => {
              this.loading = false;
              this.$message.error(e.message);
            });
      },
    }
  };
</script>

<style scoped></style>
