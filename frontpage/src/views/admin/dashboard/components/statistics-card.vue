<!-- 统计卡片 -->
<template>
  <el-row :gutter="15">
    <el-col v-bind="styleResponsive ? { lg: 6, md: 12 } : { span: 6 }">
      <el-card class="analysis-chart-card" shadow="never">
        <template v-slot:header>
          <div class="ele-cell">
            <div class="ele-cell-content">总交易金额</div>
            <el-tag size="mini" type="warning">总</el-tag>
          </div>
        </template>
        <div class="analysis-chart-card-num ele-text-heading">¥ {{total_amount}}</div>
        <el-divider />
            <div class="analysis-chart-card-text">总代付金额 ¥{{total_daifu_amount}}</div>
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
        <div class="analysis-chart-card-num ele-text-heading">{{yesterday_amount}}</div>
        <el-divider />
            <div class="analysis-chart-card-text">笔数{{yesterday_num}}, 成功率 {{yesterday_success_rate.toFixed(2)}}%</div>
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
        <div class="analysis-chart-card-num ele-text-heading">{{today_amount}}</div>
        <el-divider />
            <div class="analysis-chart-card-text">支付笔数{{today_num}}, 成功率 {{today_success_rate.toFixed(2)}}%</div>
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
        <div class="analysis-chart-card-num ele-text-heading">{{today_daifu}}</div>
        <el-divider />
            <div class="analysis-chart-card-text">昨日 {{yesterday_daifu}}</div>
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
          total_amount: '',
          total_daifu_amount: '',
          today_amount: '',
          today_num: '',
          today_success_rate: '',
          yesterday_amount: '',
          yesterday_num: '',
          yesterday_success_rate: '',
          today_daifu: '',
          yesterday_daifu: '',
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
        /* 删除 */
        getStatisticIndex() {
            const loading = this.$loading({ lock: true });
            api.statistic_index()
                .then((res) => {
                    this.total_amount = res.total_amount;
                    this.total_daifu_amount = res.total_daifu_amount;
                    this.today_amount = res.today_amount;
                    this.today_num = res.today_num;
                    this.today_success_rate = res.today_success_rate;
                    this.yesterday_amount = res.yesterday_amount;
                    this.yesterday_num = res.yesterday_num;
                    this.yesterday_success_rate = res.yesterday_success_rate;
                    this.today_daifu = res.today_daifu;
                    this.yesterday_daifu = res.yesterday_daifu;

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
