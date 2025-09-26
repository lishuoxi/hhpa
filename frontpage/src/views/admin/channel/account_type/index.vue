<template>
  <div class="ele-body">
    <el-card shadow="never">
      <!-- 搜索表单 -->
      <account-type-search @search="reload" />
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

        <!-- 操作列 -->
        <template slot="channels" slot-scope="{ row }">
            <el-tag
              v-for="tag in row.channels"
              :key="tag"
              size="mini"
              closable
              :disable-transitions="false"
              @close="removeChannel(row, tag)">
              {{tag.name}}
            </el-tag>
             <el-tag type="success" size="mini" @click="addChannel(row)">+</el-tag>
        </template>

        <!-- 操作列 -->
        <template slot="action" slot-scope="{ row }">
          <el-button
            type="primary"
            :underline="false"
            icon="el-icon-edit"
            @click="openEdit(row)"
            size="mini"
          >
            修改
          </el-button>
          <el-popconfirm
            class="ele-action"
            title="确定要删除此支付码类型吗？"
            @confirm="remove(row)"
          >
            <el-button
              type="danger"
              slot="reference"
              :underline="false"
              icon="el-icon-delete"
              size="mini"
            >
              删除
            </el-button>
          </el-popconfirm>
        </template>

      </ele-pro-table>
    </el-card>
    <!-- 编辑弹窗 -->
    <account-type-edit :visible.sync="showEdit" :data="current" @done="reload" />
    <channel-edit :visible.sync="showChannelEdit" :data="current" @done="reload" />
  </div>
</template>

<script>
  import AccountTypeSearch from './components/account-type-search';
  import AccountTypeEdit from './components/account-type-edit';
  import ChannelEdit from './components/channel-edit';
  import * as api from '@/api/admin';

  export default {
    name: 'AdminChannelAccountType',
    components: {
      AccountTypeSearch,
      AccountTypeEdit,
      ChannelEdit
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
            label: '类型名',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 60
          },
          {
            prop: 'code',
            label: '代码',
            showOverflowTooltip: true,
            minWidth: 60
          },
          {
            prop: 'channels',
            label: '关联通道',
            showOverflowTooltip: false,
            minWidth: 160,
            slot: 'channels'
          },
          {
            prop: 'accounts',
            label: '支付码数量',
            showOverflowTooltip: true,
            minWidth: 50
          },
          {
            columnKey: 'action',
            label: '操作',
            width: 260,
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
        // 是否显示导入弹窗
        showChannelEdit: false
      };
    },
    methods: {
      /* 表格数据源 */
      datasource({ page, limit, where, order }) {
        return api.account_type_page({ ...where, ...order, page, limit });
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
      addChannel(row) {
        this.current = row;
        this.showChannelEdit = true;
      },
      removeChannel(row, channel) {
        const loading = this.$loading({ lock: true });
        api.account_type_channel_remove({account_type_id:row.id, channel_id: channel.id})
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
      /* 删除 */
      remove(row) {
        const loading = this.$loading({ lock: true });
        api.account_type_remove({id:row.id})
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
