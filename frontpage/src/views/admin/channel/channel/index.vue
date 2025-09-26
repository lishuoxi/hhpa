<template>
  <div class="ele-body">
    <el-card shadow="never">
      <!-- 搜索表单 -->
      <channel-search @search="reload" />
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
        <!-- 限额 -->
        <template slot="limits" slot-scope="{ row }">
          {{row.amount_min_limit}} / {{row.amount_max_limit}}
        </template>
        <!-- 状态列 -->
        <template slot="status" slot-scope="{ row }">
          <el-switch
            active-value="开启"
            inactive-value="关闭"
            v-model="row.status"
            @change="editStatus(row)"
          />
        </template>
        <!-- 操作列 -->
        <template slot="action" slot-scope="{ row }">
          <el-button
            size="mini"
            type="primary"
            icon="el-icon-edit"
            class="ele-btn-icon"
            @click="openEdit(row)"
          >
            修改
          </el-button>

          <el-popconfirm
            class="ele-action"
            title="确定要删除此通道吗？"
            @confirm="remove(row)"
          >
            <el-button
              size="mini"
              type="danger"
              slot="reference"
              icon="el-icon-delete"
              class="ele-btn-icon"
            >
              删除
            </el-button>
          </el-popconfirm>
        </template>
      </ele-pro-table>
    </el-card>
    <!-- 编辑弹窗 -->
    <channel-edit :visible.sync="showEdit" :data="current" @done="reload" />
  </div>
</template>

<script>
  import ChannelSearch from './components/channel-search';
  import ChannelEdit from './components/channel-edit';
  import * as api from '@/api/admin';

  export default {
    name: 'ChannelChannel',
    components: {
      ChannelSearch,
      ChannelEdit,
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
            prop: 'name',
            label: '名称',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'code',
            label: '代码',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            label: '最小/最大金额',
            showOverflowTooltip: true,
            minWidth: 110,
            slot: 'limits'
          },
          {
            prop:'amount_day_limit',
            label: '单日限量',
            showOverflowTooltip: true,
            minWidth: 80
          },
          {
            prop:'fixed_amounts',
            label: '固定金额',
            showOverflowTooltip: true,
            minWidth: 110
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
            columnKey: 'action',
            label: '操作',
            width: 300,
            align: 'center',
            resizable: false,
            slot: 'action'
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
        return api.channel_page({ ...where, ...order, page, limit});
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
      remove(row) {
        const loading = this.$loading({ lock: true });
        api.channel_remove({id:row.id})
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
      editStatus(row) {
        const loading = this.$loading({ lock: true });
        api.channel_update_status({id:row.id, status:row.status})
          .then(() => {
            loading.close();
            this.$message.success('操作成功');
          })
          .catch((e) => {
            loading.close();
            row.status = row.status=='开启' ? '关闭' : '开启';
            this.$message.error(e.message);
          });
      }

    }
  };
</script>

<style scoped></style>
