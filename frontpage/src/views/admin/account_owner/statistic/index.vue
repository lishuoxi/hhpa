<template>
    <div class="ele-body">
        <el-card shadow="never">
            <!-- 搜索表单 -->
            <statistic-search @search="reload" />
                <!-- 数据表格 -->
                <ele-pro-table
                        ref="table"
                        :columns="columns"
                        :datasource="datasource"
                        >
                        <!-- 表头工具栏 -->
                    <template slot="toolbar">
                    </template>

                    <template slot="num" slot-scope="{ row }">
                        <span v-if="row.success">{{row.success.trade_count}}</span> /
                        <span v-if="row.all">{{row.all.trade_count}}</span>
                    </template>

                    <template slot="amount" slot-scope="{ row }">
                        <span v-if="row.success">{{row.success.amount_total}}</span> /
                        <span v-if="row.all">{{row.all.amount_total}}</span>
                    </template>

                    <template slot="success_rate" slot-scope="{ row }">
                        <span v-if="row.all && row.success && row.all.trade_count!=0">
                            {{(100.0*row.success.trade_count / row.all.trade_count).toFixed(2)}} %
                        </span>
                        <span v-else>/</span>
                    </template>

                    <template slot="today_num" slot-scope="{ row }">
                        <span v-if="row.success_today">{{row.success_today.trade_count}}</span> /
                        <span v-if="row.all_today">{{row.all_today.trade_count}}</span>
                    </template>

                    <template slot="today_amount" slot-scope="{ row }">
                        <span v-if="row.success_today">{{row.success_today.amount_total}}</span> /
                        <span v-if="row.all_today">{{row.all_today.amount_total}}</span>
                    </template>

                    <template slot="success_today_rate" slot-scope="{ row }">
                        <span v-if="row.all_today && row.success_today && row.all_today.trade_count!=0">
                            {{ (100.0*row.success_today.trade_count / row.all_today.trade_count).toFixed(2)}} %
                        </span>
                        <span v-else>/</span>
                    </template>


                </ele-pro-table>
        </el-card>
        <!-- 编辑弹窗 -->
        <recharge-edit :visible.sync="showEdit" :data="current" @done="reload" />
    </div>
</template>

<script>
    import StatisticSearch from './components/statistic-search';
    import * as api from '@/api/admin';

    export default {
        name: 'AccountOwnerStatistic',
        components: {
            StatisticSearch,
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
                        minWidth: 60,
                    },
                    {
                        label: '笔数',
                        showOverflowTooltip: true,
                        minWidth: 60,
                        slot: 'num'
                    },
                    {
                        label: '总量',
                        showOverflowTooltip: true,
                        minWidth: 80,
                        slot: 'amount'
                    },
                    {
                        label: '总成功率',
                        showOverflowTooltip: true,
                        minWidth: 60,
                        slot: 'success_rate'
                    },
                    {
                        label: '今日笔数',
                        showOverflowTooltip: true,
                        minWidth: 60,
                        slot: 'today_num'
                    },
                    {
                        label: '今日总量',
                        showOverflowTooltip: true,
                        minWidth: 80,
                        slot: 'today_amount'
                    },
                    {
                        label: '今日总成功率',
                        showOverflowTooltip: true,
                        minWidth: 60,
                        slot: 'success_today_rate'
                    },
                    {
                        prop: 'level1',
                        label: '码利润',
                        showOverflowTooltip: true,
                        minWidth: 60,
                    },
                    {
                        prop: 'level2',
                        label: '总下级提成',
                        showOverflowTooltip: true,
                        minWidth: 60,
                    },
                    {
                        prop: 'today_level1',
                        label: '今日码利润',
                        showOverflowTooltip: true,
                        minWidth: 60,
                    },
                    {
                        prop: 'today_level2',
                        label: '今日下级提成',
                        showOverflowTooltip: true,
                        minWidth: 60,
                    },

                ],
            };
        },
        methods: {
            /* 表格数据源 */
            datasource({ page, limit, where, order }) {
                return api.statistic_account_owner({ ...where, ...order, page, limit });
            },
            /* 刷新表格 */
            reload(where) {
                this.$refs.table.reload({ page: 1, where: where });
            },

        }
    };
</script>

<style scoped></style>
