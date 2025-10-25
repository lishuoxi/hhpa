<!-- 统计卡片 -->
<template>
  <el-row :gutter="15">
    <el-col v-bind="styleResponsive ? { lg: 6, md: 12 } : { span: 6 }">
      <el-card class="analysis-chart-card" shadow="never">
        <template v-slot:header>
          <div class="ele-cell">
            <div class="ele-cell-content">总交易金额</div>
            <el-tag size="mini" type="warning">累计</el-tag>
          </div>
        </template>
        <div class="analysis-chart-card-num ele-text-heading">¥ {{ total_amount }}</div>
        <el-divider />
        <div class="analysis-chart-card-text">总代付金额 ¥{{ total_daifu_amount }}</div>
      </el-card>
    </el-col>
    <el-col v-bind="styleResponsive ? { lg: 6, md: 12 } : { span: 6 }">
      <el-card class="analysis-chart-card" shadow="never">
        <template v-slot:header>
          <div class="ele-cell">
            <div class="ele-cell-content">交易成功金额</div>
            <el-tag size="mini" type="primary">昨日</el-tag>
          </div>
        </template>
        <div class="analysis-chart-card-num ele-text-heading">{{ yesterday_amount }}</div>
        <el-divider />
        <div class="analysis-chart-card-text">笔数 {{ yesterday_num }}, 成功率 {{ (Number(yesterday_success_rate) || 0).toFixed(2) }}%</div>
      </el-card>
    </el-col>
    <el-col v-bind="styleResponsive ? { lg: 6, md: 12 } : { span: 6 }">
      <el-card class="analysis-chart-card" shadow="never">
        <template v-slot:header>
          <div class="ele-cell">
            <div class="ele-cell-content">交易成功金额</div>
            <el-tag type="success" size="mini">今日</el-tag>
          </div>
        </template>
        <div class="analysis-chart-card-num ele-text-heading">{{ today_amount }}</div>
        <el-divider />
        <div class="analysis-chart-card-text">支付笔数 {{ today_num }}, 成功率 {{ (Number(today_success_rate) || 0).toFixed(2) }}%</div>
      </el-card>
    </el-col>
    <el-col v-bind="styleResponsive ? { lg: 6, md: 12 } : { span: 6 }">
      <el-card class="analysis-chart-card" shadow="never">
        <template v-slot:header>
          <div class="ele-cell">
            <div class="ele-cell-content">代付金额</div>
            <el-tag size="mini" type="success">今日</el-tag>
          </div>
        </template>
        <div class="analysis-chart-card-num ele-text-heading">{{ today_daifu }}</div>
        <el-divider />
        <div class="analysis-chart-card-text">昨日 {{ yesterday_daifu }}</div>
      </el-card>
    </el-col>
  </el-row>
</template>

<script>
  import * as api from '@/api/admin';

  export default {
    components: { },
    data() {
      return {
        total_amount: 0,
        total_daifu_amount: 0,
        today_amount: 0,
        today_num: 0,
        today_success_rate: 0,
        yesterday_amount: 0,
        yesterday_num: 0,
        yesterday_success_rate: 0,
        today_daifu: 0,
        yesterday_daifu: 0,
      };
    },
    computed: {
      // 是否开启响应式布局
      styleResponsive() {
        return this.$store.state.theme.styleResponsive;
      }
    },
    created() {
      this.getStatisticIndex();
    },
    methods: {
      /* 获取统计数据 */
      getStatisticIndex() {
        const loading = this.$loading({ lock: true });
        api.statistic_index()
          .then((res) => {
            this.total_amount = Number(res.total_amount) || 0;
            this.total_daifu_amount = Number(res.total_daifu_amount) || 0;
            this.today_amount = Number(res.today_amount) || 0;
            this.today_num = Number(res.today_num) || 0;
            this.today_success_rate = Number(res.today_success_rate) || 0;
            this.yesterday_amount = Number(res.yesterday_amount) || 0;
            this.yesterday_num = Number(res.yesterday_num) || 0;
            this.yesterday_success_rate = Number(res.yesterday_success_rate) || 0;
            this.today_daifu = Number(res.today_daifu) || 0;
            this.yesterday_daifu = Number(res.yesterday_daifu) || 0;

            loading.close();
          })
          .catch((e) => {
            loading.close();
            this.$message.error(e.message);
          });
      },

    }
  };
</script>

<style lang="scss" scoped>
  .analysis-chart-card-num {
    font-size: 30px;
  }

  .analysis-chart-card-content {
    height: 40px;
    box-sizing: border-box;
    margin-bottom: 12px;
  }

  .analysis-chart-card-text {
    padding-top: 12px;
  }
</style>
