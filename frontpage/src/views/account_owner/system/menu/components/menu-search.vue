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
        <el-form-item label="菜单名称:">
          <el-input clearable v-model="where.title" placeholder="请输入" />
        </el-form-item>
      </el-col>
      <el-col :lg="6" :md="12">
        <el-form-item label="菜单地址:">
          <el-input clearable v-model="where.path" placeholder="请输入" />
        </el-form-item>
      </el-col>
      <el-col :lg="6" :md="12">
        <el-form-item label="权限标识:">
          <el-input clearable v-model="where.authority" placeholder="请输入" />
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
    title: '',
    path: '',
    authority: ''
  };

  export default {
    name: 'MenuSearch',
    data() {
      return {
        // 表单数据
        where: { ...DEFAULT_WHERE }
      };
    },
    methods: {
      /* 搜索 */
      search() {
        this.$emit('search', this.where);
      },
      /*  重置 */
      reset() {
        this.where = { ...DEFAULT_WHERE };
        this.search();
      }
    }
  };
</script>

<style scoped></style>
