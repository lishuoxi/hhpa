<!-- 搜索表单 -->
<template>
  <el-form
    label-width="77px"
    class="ele-form-search"
    @keyup.enter.native="search"
    @submit.native.prevent
  >
    <el-row :gutter="15">
      <el-col :lg="6" :md="12">
        <el-form-item label="码商名称:">
          <el-input clearable v-model="where.name" placeholder="请输入" />
        </el-form-item>
      </el-col>

      <el-col :lg="8" :md="24">
        <el-form-item label="时间:" size="mini">
          <el-date-picker
            v-model="dateRange"
            type="datetimerange"
            :picker-options="pickerOptions"
            unlink-panels
            range-separator="-"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            value-format="yyyy-MM-dd HH:mm:ss"
            class="ele-fluid"
          />
        </el-form-item>
      </el-col>
      <el-col :lg="6" :md="12">
        <div class="ele-form-actions">
          <el-button
            type="primary"
            icon="el-icon-search"
            class="ele-btn-icon"
            @click="search"
          >
            查询
          </el-button>
          <el-button @click="reset">重置</el-button>
        </div>
      </el-col>
    </el-row>
  </el-form>
</template>

<script>
  const DEFAULT_WHERE = {
    name: '',
  };

  export default {
    name: 'StatisticSearch',
    data() {
      return {
        // 表单数据
        where: { ...DEFAULT_WHERE },
        dateRange: [],
        // 日期时间选择器快捷项
        pickerOptions: {
          shortcuts: [
            {
              text: '当前小时',
              onClick(picker) {
                const end = new Date();
                var start = new Date();
                start.setHours(start.getHours(), 0, 0, 0);
                end.setTime(start.getTime() + 3600*1000);
                picker.$emit('pick', [start, end]);
              }
            },
            {
              text: '上一小时',
              onClick(picker) {
                const end = new Date();
                var start = new Date();
                end.setHours(end.getHours(), 0, 0, 0);
                start.setTime(end.getTime() - 3600*1000);
                picker.$emit('pick', [start, end]);
              }
            },
            {
              text: '今天',
              onClick(picker) {
                const end = new Date();
                var start = new Date();
                start.setHours(0, 0, 0, 0);
                end.setTime(start.getTime() + 3600*1000*24);
                picker.$emit('pick', [start, end]);
              }
            },
            {
              text: '昨天',
              onClick(picker) {
                const end = new Date();
                var start = new Date();
                start.setHours(0, 0, 0, 0);
                end.setHours(0, 0, 0, 0);
                start.setTime(start.getTime() - 3600*24*1000);
                picker.$emit('pick', [start, end]);
              }
            },
            {
              text: '最近一小时',
              onClick(picker) {
                const end = new Date();
                const start = new Date();
                start.setTime(start.getTime() - 3600*1000);
                picker.$emit('pick', [start, end]);
              }
            },
            {
              text: '最近一天',
              onClick(picker) {
                const end = new Date();
                const start = new Date();
                start.setTime(start.getTime() - 3600 * 1000 * 24);
                picker.$emit('pick', [start, end]);
              }
            },
          ]
        }
      };
    },
    methods: {
      /* 搜索 */
      search() {
        const [d1, d2] = this.dateRange;
          this.$emit('search', {...this.where, 
              started: d1 ?? '',
              ended: d2 ?? ''
          });
      },
      /*  重置 */
      reset() {
        this.dateRange = [];
        this.where = { ...DEFAULT_WHERE };
        this.search();
      }
    }
  };
</script>

<style scoped></style>
