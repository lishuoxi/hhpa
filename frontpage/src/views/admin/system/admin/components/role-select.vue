<!-- 角色选择下拉框 -->
<template>
  <el-select
    multiple
    clearable
    :value="value"
    class="ele-block"
    :placeholder="placeholder"
    @change="updateValue"
  >
    <el-option
      v-for="item in data"
      :key="item.roleId"
      :value="item.roleId"
      :label="item.roleName"
    />
  </el-select>
</template>

<script>

  export default {
    name: 'RoleSelect',
    model: {
      prop: 'value',
      event: 'change'
    },
    props: {
      // 选中的数据(v-modal)
      value: Array,
      // 提示信息
      placeholder: {
        type: String,
        default: '请选择角色'
      }
    },
    data() {
      return {
        data: []
      };
    },
    created() {
    },
    methods: {
      updateValue(value) {
        this.$emit('change', value);
      }
    }
  };
</script>

<style scoped></style>
