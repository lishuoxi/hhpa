<!-- 角色选择下拉框 -->
<template>
  <el-select
    clearable
    :value="value"
    class="ele-block"
    :placeholder="placeholder"
    @change="updateValue"
  >
    <el-option
      v-for="item in data"
      :key="item.id"
      :value="item.id"
      :label="item.username"
    />
  </el-select>
</template>

<script>
  import * as api from '@/api/admin';
  export default {
    name: 'AccountOwnerSelect',
    model: {
      prop: 'value',
      event: 'change'
    },
    props: {
      // 选中的数据(v-modal)
      value: Number,
      // 提示信息
      placeholder: {
        type: String,
        default: '请选择所有者'
      }
    },
    data() {
      return {
        data: []
      };
    },
    created() {
      api.user_lists({role_id: 4})
        .then((res) => {
            this.data = res;
        })
        .catch(() => {
          console.log('加载数据失败');
        });
    },
    methods: {
      updateValue(value) {
        this.$emit('change', value);
      }
    }
  };
</script>

<style scoped></style>
