<template>
  <div class="ele-body">
    <el-card shadow="never">
      <!-- 搜索表单 -->
      <user-search @search="reload" />
      <!-- 数据表格 -->
      <ele-pro-table
        ref="table"
        :columns="columns"
        :datasource="datasource"
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
         <template slot="username" slot-scope="{ row }">
            {{row.realname}} <br/> {{row.username}}
        </template>

        <template slot="shangji" slot-scope="{ row }">
            {{row.shangji?.username}}
        </template>

        <template slot="balance" slot-scope="{ row }">
            {{row.balance}}  
            <el-link size="mini" type="success">
                <i class="el-icon-edit" type="danger" @click="openEditBalance(row)"></i>
            </el-link> <br/>
            ({{row.balance_lock}})
        </template>

        <template slot="daifu_balance" slot-scope="{ row }">
            {{row.daifu_balance}}  
            <el-link size="mini" type="success">
                <i class="el-icon-edit" type="danger" @click="openEditBalance(row)"></i>
            </el-link> <br/>
            ({{row.daifu_balance_lock}})
        </template>

        <template slot="rates" slot-scope="{ row }">
                        <template v-for="(tag, index) in row.owner_channels" >
                            <el-tag size="mini" :key="tag.id"
                                    closable
                                    :disable-transitions="false"
                                    @close="handleClose(row, tag)"
                                    @click="editChannel(row, tag)"
                                    >
                                    {{tag.name}}_{{tag.pivot.rate}}
                            </el-tag> 
                            <br v-if="(index%2==1) && (index!=row.owner_channels.length-1)" :key="tag.id" />
                        </template>
                        <el-tag type="success" size="mini" @click="addChannel(row)">+</el-tag>
        </template>

        <!-- 状态列 -->
        <template slot="status" slot-scope="{ row }">
          <el-switch
            active-value="启用"
            inactive-value="冻结"
            active-text="启用"
            v-model="row.status"
            @change="editStatus(row)"
            size="mini"
            /> <br/>

            <el-switch
                size="mini"
                active-value="开启"
                inactive-value="关闭"
                active-text="接单"
                v-model="row.jiedan_status"
                @change="editJiedanStatus(row)"
                />
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
          <el-button
            type="warning"
            :underline="false"
            icon="el-icon-key"
            @click="resetPsw(row)"
            size="mini"
          >
            重置密码
          </el-button> <br />
          <el-popconfirm
            class="ele-action"
            title="确定要删除此用户吗？"
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
          <el-button
            type="success"
            :underline="false"
            icon="el-icon-key"
            @click="resetGoogle(row)"
            size="mini"
          >
            重置谷歌
          </el-button>
        </template>
      </ele-pro-table>
    </el-card>
    <!-- 编辑弹窗 -->
    <user-edit :visible.sync="showEdit" :data="current" @done="reload" />
    <!-- 导入弹窗 -->
    <channel-edit :visible.sync="showChannelEdit" @done="reload" :data="current" :channel="channel"/>

        <user-edit-balance :visible.sync="showEditBalance" :data="current" @done="reload" />
  </div>
</template>

<script>
  import UserSearch from './components/user-search';
  import UserEdit from './components/user-edit';
  import ChannelEdit from './components/channel-edit';
    import UserEditBalance from './components/user-edit-balance';
  import * as api from '@/api/admin';

  export default {
    name: 'AccountOwner',
    components: {
      UserSearch,
      UserEdit,
      ChannelEdit,
        UserEditBalance
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
            prop: 'username',
            label: '用户名',
            showOverflowTooltip: true,
            minWidth: 90,
            slot:"username"
          },
          {
            prop: 'balance',
            label: '余额',
            showOverflowTooltip: true,
            minWidth: 80,
              slot: "balance"
          },
          {
            prop: 'daifu_balance',
            label: '代付余额',
            showOverflowTooltip: true,
            minWidth: 80,
              slot: "daifu_balance"
          },
          {
            prop: 'pid',
            label: '上级',
            showOverflowTooltip: true,
            minWidth: 80,
            slot: 'shangji'
          },
          {
            label: '费率',
            showOverflowTooltip: true,
            minWidth: 210,
            slot: 'rates'
          },
          {
            prop: 'created_at',
            label: '创建时间',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 80,
            formatter: (row, column, cellValue) => {
              return this.$util.toDateString(cellValue, 'MM-dd HH:mm:ss');
            }
          },
          {
            prop: 'status',
            label: '状态',
            align: 'center',
            width: 120,
            resizable: false,
            slot: 'status'
          },
          {
            columnKey: 'action',
            label: '操作',
            width: 220,
            align: 'center',
            resizable: false,
            slot: 'action'
          }
        ],
        // 当前编辑数据
        current: null,
        // 是否显示编辑弹窗
        showEdit: false,
        // 是否显示导入弹窗
        showChannelEdit: false,
          showEditBalance: false,
        role_id: 4,
        channel: null,
      };
    },
    methods: {
      /* 表格数据源 */
      datasource({ page, limit, where, order }) {
        return api.user_page({ ...where, ...order, page, limit, role_id:this.role_id });
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
        /* 打开编辑弹窗 */
        openEditBalance(row) {
            this.current = row;
            this.showEditBalance = true;
        },
      addChannel(row) {
        this.channel = null;
        this.current = row;
        this.showChannelEdit = true;
      },
      editChannel(row, chan) {
          this.channel = chan;
          this.current = row;
          this.showChannelEdit = true;
      },
      deleteChannel(row, channel) {
        this.$confirm('确定要删除选中的通道么?', '提示', {
          type: 'warning'
        })
          .then(() => {
            const loading = this.$loading({ lock: true });
			  api.user_account_owner_channel_remove({id: row.id, channel_id: channel.id})
              .then(() => {
                loading.close();
                this.$message.success('操作成功');
                this.reload();
              })
              .catch((e) => {
                loading.close();
                this.$message.error(e.message);
              });
          })
          .catch(() => {});
      },
      /* 删除 */
      remove(row) {
        const loading = this.$loading({ lock: true });
        api.user_remove({id:row.id})
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
      /* 重置用户密码 */
      resetPsw(row) {
        this.$confirm('确定要重置此用户的密码为"123456"吗?', '提示', {
          type: 'warning'
        })
          .then(() => {
            const loading = this.$loading({ lock: true });
			  api.user_update_password({id:row.id})
              .then(() => {
                loading.close();
                this.$message.success('重置成功');
              })
              .catch((e) => {
                loading.close();
                this.$message.error(e.message);
              });
          })
          .catch(() => {});
      },
      /* 重置用户密码 */
      resetGoogle(row) {
        this.$confirm('确定要重置此用户的谷歌为空吗?', '提示', {
          type: 'warning'
        })
          .then(() => {
            const loading = this.$loading({ lock: true });
          api.user_update_google({id:row.id})
              .then(() => {
                loading.close();
                this.$message.success('重置成功');
              })
              .catch((e) => {
                loading.close();
                this.$message.error(e.message);
              });
          })
          .catch(() => {});
      },
      /* 重置用户密码 */
      resetSecurePwd(row) {
        this.$confirm('确定要重置此用户的密码为"123456"吗?', '提示', {
          type: 'warning'
        })
          .then(() => {
            const loading = this.$loading({ lock: true });
        api.user_update_password({id:row.id})
              .then(() => {
                loading.close();
                this.$message.success('重置成功');
              })
              .catch((e) => {
                loading.close();
                this.$message.error(e.message);
              });
          })
          .catch(() => {});
      },
      /* 更改状态 */
      editStatus(row) {
        const loading = this.$loading({ lock: true });
        api.user_update_status({id:row.id, status:row.status})
          .then(() => {
            loading.close();
            this.$message.success('操作成功');
          })
          .catch((e) => {
            loading.close();
            row.status = row.status=='启用' ? '关闭' : '启用';
            this.$message.error(e.message);
          });
      },

      editJiedanStatus(row) {
        const loading = this.$loading({ lock: true });
        api.user_update_jiedan_status({id:row.id, jiedan_status:row.jiedan_status})
          .then(() => {
            loading.close();
            this.$message.success('操作成功');
          })
          .catch((e) => {
            loading.close();
            row.jiedan_status = row.jiedan_status=='开启' ? '关闭' : '开启';
            this.$message.error(e.message);
          });
      }
    }
  };
</script>

<style scoped></style>
