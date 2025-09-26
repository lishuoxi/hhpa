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
                        :selection.sync="selection"
                        >
                        <!-- 表头工具栏 -->
                    <template slot="toolbar">
                        <el-button
                                size="mini"
                                type="primary"
                                icon="el-icon-plus"
                                class="ele-btn-icon"
                                @click="openEdit()"
                                >
                                新建
                        </el-button>

                    </template>
                    <!-- 用户名 -->
                    <template slot="username" slot-scope="{ row }">
                        {{row.username}} 
                    </template>
                    <!-- 用户名 -->
                    <template slot="balance" slot-scope="{ row }">
                        {{row.balance}}  
                        <el-link size="mini" type="success">
                            <i class="el-icon-edit" type="danger" @click="openEditBalance(row)"></i>
                        </el-link>
<br/>
                        ({{row.balance_lock}})

                    </template>

                    <!-- 用户名 -->
                    <template slot="daifu_balance" slot-scope="{ row }">
                        {{row.daifu_balance}} 
                        <el-link size="mini" type="success">
                            <i class="el-icon-edit" type="danger" @click="openEditBalance(row)"></i>
                        </el-link>
                        <br/>
                        ({{row.daifu_balance_lock}})

                    </template>
                    <!-- 商户号和key -->
                    <template slot="apis" slot-scope="{ row }">
                        {{row.merchant_id}}<br/>{{row.merchant_key}}
                    </template>

                    <!-- 状态列 -->
                    <template slot="status" slot-scope="{ row }">
                        <el-switch
                                active-value="启用"
                                inactive-value="冻结"
                                v-model="row.status"
                                @change="editStatus(row)"
                                />
                    </template>

                    <!-- 通道配置 -->
                    <template slot="channels" slot-scope="{ row }">

                        <template v-for="(tag, index) in row.channels" >
                            <el-tag size="mini" :key="tag.id"
                                    closable
                                    :disable-transitions="false"
                                    @close="handleClose(row, tag)"
                                    @click="editChannel(row, tag)"
                                    >
                                    {{tag.name}}_{{tag.pivot.rate}}
                            </el-tag> 
                                <br v-if="index%2==1 && (index!=row.channels.length-1)" :key="tag.id" />
                        </template>
                            <el-tag type="success" size="mini" @click="addChannel(row)">+</el-tag>

                    </template>

                    <!-- 操作列 -->
                    <template slot="action" slot-scope="{ row }">
                        <div>
                            <el-popconfirm
                                    class="ele-action"
                                    title="确定要重置用户密码为123456吗？"
                                    @confirm="resetPsw(row)"
                                    >
                                    <el-link
                                            size="mini"
                                            type="warning"
                                            :underline="false"
                                            icon="el-icon-key"
                                            slot="reference"
                                            >重置密码</el-link>
                            </el-popconfirm>
                        </div>
                        <div>

                            <el-popconfirm
                                    class="ele-action"
                                    title="确定要重置谷歌密码吗？"
                                    @click="resetGoogle(row)"
                                    >
                                    <el-link
                                            size="mini"
                                            type="success"
                                            :underline="false"
                                            icon="el-icon-key"
                                            slot="reference"
                                            >重置谷歌
                                    </el-link>
                            </el-popconfirm>
                        </div>
                        <div>

                            <el-popconfirm
                                    class="ele-action"
                                    title="确定要重置支付密码吗？"
                                    @click="resetSecurePwd(row)"
                                    >
                                    <el-link
                                            size="mini"
                                            type="primary"
                                            :underline="false"
                                            icon="el-icon-key"
                                            slot="reference"
                                            >重置支付密码
                                    </el-link>
                            </el-popconfirm>
                        </div>
                    </template>
                </ele-pro-table>
        </el-card>
        <user-edit-balance :visible.sync="showEditBalance" :data="current" @done="reload" />
        <user-edit :visible.sync="showEdit" :data="current" @done="reload" />
        <channel-edit :visible.sync="showChannelEdit" :data="current" @done="reload" :channel="channel" />
    </div>
</template>

<script>
    import UserSearch from './components/user-search';
    import UserEdit from './components/user-edit';
    import UserEditBalance from './components/user-edit-balance';
    import ChannelEdit from './components/channel-edit';
    import * as api from '@/api/admin';

    export default {
        name: 'MerchantMerchant',
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
                        prop: 'username',
                        label: '用户名',
                        showOverflowTooltip: true,
                        minWidth: 60,
                        slot: "username"
                    },
                    {
                        prop: 'balance',
                        label: '余额',
                        minWidth: 60,
                        slot: 'balance'
                    },
                    {
                        prop: 'daifu_balance',
                        label: '代付余额',
                        minWidth: 60,
                        slot: 'daifu_balance'
                    },
                    {
                        prop: 'merchant_id',
                        label: '对接信息',
                        showOverflowTooltip: true,
                        minWidth: 80,
                        slot: 'apis'
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
                        label: '通道',
                        minWidth: 180,
                        slot:'channels'
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
                        width: 160,
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
                showEditBalance: false,
                // 是否显示导入弹窗
                showChannelEdit: false,
                role_id: 2,
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
            handleClose(row, chan) {
                this.$confirm('确定要删除选中的通道么?', '提示', {
                    type: 'warning'
                })
                    .then(() => {
                        const loading = this.$loading({ lock: true });
                        api.user_merchant_channel_remove({id: row.id, channel_id: chan.id})
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
            /* 重置用户密码 */
            resetPsw(row) {
                const loading = this.$loading({ lock: true });
                api.user_update_password({id:row.id})
                    .then(() => {
                        loading.close();
                        this.$message.success('重置成功');
                    }) .catch((e) => {
                        loading.close();
                        this.$message.error(e.message);
                    });
            },
            /* 重置用户密码 */
            resetGoogle(row) {
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
            },
            /* 重置用户支付密码 */
            resetSecurePwd(row) {
                const loading = this.$loading({ lock: true });
                api.user_update_secure_password({id:row.id})
                    .then(() => {
                        loading.close();
                        this.$message.success('重置成功');
                    })
                    .catch((e) => {
                        loading.close();
                        this.$message.error(e.message);
                    });
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
                        row.status = row.status=='启用' ? '冻结' : '启用';
                        this.$message.error(e.message);
                    });
            }
        }
    };
</script>

<style scoped></style>
