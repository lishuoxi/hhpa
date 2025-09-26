<!-- 详情弹窗 -->
<template>
  <el-dialog
    title="详情"
    width="680px"
    :visible="visible"
    @update:visible="updateVisible"
  >
    <el-form size="mini" label-width="82px" class="ele-form-detail">
      <el-row :gutter="15">
        <el-col :sm="12">
          <el-form-item label="操作人:">
            <div class="ele-text-secondary">
              {{ data.nickname }}({{ data.username }})
            </div>
          </el-form-item>
          <el-form-item label="操作模块:">
            <div class="ele-text-secondary">
              {{ data.module }}
            </div>
          </el-form-item>
          <el-form-item label="操作时间:">
            <div class="ele-text-secondary">
              {{ data.createTime }}
            </div>
          </el-form-item>
          <el-form-item label="请求方式:">
            <div class="ele-text-secondary">
              {{ data.requestMethod }}
            </div>
          </el-form-item>
        </el-col>
        <el-col :sm="12">
          <el-form-item label="IP地址:">
            <div class="ele-text-secondary">
              {{ data.ip }}
            </div>
          </el-form-item>
          <el-form-item label="操作功能:">
            <div class="ele-text-secondary">
              {{ data.description }}
            </div>
          </el-form-item>
          <el-form-item label="请求耗时:">
            <div v-if="!isNaN(data.spendTime)" class="ele-text-secondary">
              {{ data.spendTime / 1000 }}s
            </div>
          </el-form-item>
          <el-form-item label="请求状态:">
            <el-tag :type="['success', 'danger'][data.status]" size="mini">
              {{ ['正常', '异常'][data.status] }}
            </el-tag>
          </el-form-item>
        </el-col>
      </el-row>
      <div style="margin: 12px 0">
        <el-divider />
      </div>
      <el-row :gutter="15">
        <el-col :sm="12">
          <el-form-item label="操作系统:">
            <div class="ele-text-secondary">
              {{ data.os }}
            </div>
          </el-form-item>
        </el-col>
        <el-col :sm="12">
          <el-form-item label="浏览器:">
            <div class="ele-text-secondary">
              {{ data.browser }}
            </div>
          </el-form-item>
        </el-col>
      </el-row>
      <el-form-item label="设备名称:">
        <div class="ele-text-secondary">
          {{ data.device }}
        </div>
      </el-form-item>
      <el-form-item label="请求地址:">
        <div class="ele-text-secondary">
          {{ data.url }}
        </div>
      </el-form-item>
      <el-form-item label="调用方法:">
        <div class="ele-text-secondary">
          {{ data.method }}
        </div>
      </el-form-item>
      <el-form-item label="请求参数:">
        <div class="ele-text-secondary">
          {{ data.params }}
        </div>
      </el-form-item>
      <el-form-item v-if="data.status === 0" label="返回结果:">
        <div class="ele-text-secondary">
          {{ data.result }}
        </div>
      </el-form-item>
      <el-form-item v-else label="异常信息:">
        <div class="ele-text-secondary">
          {{ data.error }}
        </div>
      </el-form-item>
    </el-form>
    <div slot="footer">
      <el-button @click="updateVisible(false)">关闭</el-button>
    </div>
  </el-dialog>
</template>

<script>
  export default {
    name: 'OperRecordDetail',
    props: {
      // 弹窗是否打开
      visible: Boolean,
      // 数据
      data: {
        type: Object,
        required: true
      }
    },
    methods: {
      /* 更新visible */
      updateVisible(value) {
        this.$emit('update:visible', value);
      }
    }
  };
</script>

<style scoped></style>
