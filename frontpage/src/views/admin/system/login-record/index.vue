<template>
  <div class="ele-body">
    <el-card shadow="never">
      <!-- 搜索表单 -->
      <login-record-search @search="reload" />
      <!-- 数据表格 -->
      <ele-pro-table ref="table" :datasource="datasource" :columns="columns">
        <!-- 表头工具栏 -->
        <!-- 操作类型列 -->
        <template slot="type" slot-scope="{ row }">
          <el-tag
            size="mini"
            :disable-transitions="true"
            :type="['success', 'danger', 'info', 'warning'][row.type]"
          >
            {{
              ['登录成功', '登录失败', '退出登录', '刷新TOKEN'][row.type]
            }}
          </el-tag>
        </template>
      </ele-pro-table>
    </el-card>
  </div>
</template>

<script>
  //import XLSX from 'xlsx';
  import LoginRecordSearch from './components/login-record-search';
  import * as api from '@/api/admin';

  export default {
    name: 'SystemLoginRecord',
    components: { LoginRecordSearch },
    data() {
      return {
        // 表格列配置
        columns: [
          {
            columnKey: 'index',
            type: 'index',
            width: 45,
            align: 'center',
            showOverflowTooltip: true,
            fixed: 'left'
          },
          {
            prop: 'username',
            label: '账号',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'ip',
            label: 'IP地址',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'device',
            label: '设备型号',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'os',
            label: '操作系统',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'browser',
            label: '浏览器',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'type',
            label: '操作类型',
            showOverflowTooltip: true,
            minWidth: 110,
            slot: 'type'
          },
          {
            prop: 'comments',
            label: '备注',
            showOverflowTooltip: true,
            minWidth: 110
          },
          {
            prop: 'created_at',
            label: '登录时间',
            sortable: 'custom',
            showOverflowTooltip: true,
            minWidth: 110,
            formatter: (row, column, cellValue) => {
              return this.$util.toDateString(cellValue);
            }
          }
        ]
      };
    },
    methods: {
      /* 表格数据源 */
      datasource({ page, limit, where, order }) {
        return api.login_record_page({ ...where, ...order, page, limit });
      },
      /* 刷新表格 */
      reload(where) {
        this.$refs.table.reload({ page: 1, where: where });
      },
      /* 导出数据 */
      exportData() {
        const array = [
          [
            '账号',
            'IP地址',
            '设备型号',
            '操作系统',
            '浏览器',
            '操作类型',
            '备注',
            '登录时间'
          ]
        ];
        const loading = this.$loading({ lock: true });
        api.login_record_lists()
          .then((data) => {
            loading.close();
            data.forEach((d) => {
              array.push([
                d.username,
                d.ip,
                d.device,
                d.os,
                d.browser,
                ['登录成功', '登录失败', '退出登录', '刷新TOKEN'][d.type],
                d.comments,
                this.$util.toDateString(d.created_at)
              ]);
            });
            //this.$util.exportSheet(XLSX, array, '登录日志');
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
