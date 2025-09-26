<template>
  <div>
    <template v-for="(item) in menuList">
      <!-- 有次级菜单的,则展开子选项-->
      <el-sub-menu v-if="item.children && item.children.length>0" :key="item.id" :index="item.id">
        <template #title>
		  <el-icon v-if="item.icon!=''"><component :is="item.icon" /></el-icon>
          <span :class="[item.parentid==0 ?'activespan':'disactivesapn']">{{item.name}}</span>
        </template>
        <!-- 递归,实现无限级展开 -->
        <MenuTree :menuList="item.children"></MenuTree>
      </el-sub-menu>
      <!-- 没有次级菜单的 -->
      <el-menu-item  v-if="!item.children" :key="item.id" :index="item.url">
		  <el-icon v-if="item.icon!=''"><component :is="item.icon" /></el-icon>
		  <span :class="[item.parentid==0 ?'activespan':'disactivesapn']">{{item.name}}</span>
      </el-menu-item>
    </template>
  </div>
</template>
<script>
export default {
  props:{
	  menuList:{
		  type:Array,
		  default(){
			  return []
		  }
	  }
  },
  name: 'MenuTree',
  methods: {
  }
}
</script>
<style scoped>
.activespan{
	font-size: 15px !important;
}
.disactivesapn{
	margin-left: 20px;
	font-size: 15px !important;
}
/* 菜单栏选中后背景色 */
.el-menu-item {
	 color: #ffffff;
 } 
.el-menu-item.is-active {
	 color: #55aaff;
}	
</style>
