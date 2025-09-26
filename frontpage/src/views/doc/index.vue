<template>
    <el-container class="el-container">
        <!-- 页面头部区域 高度默认60px -->
        <el-header class="el-header">
            <!-- 应用名称 -->
            <span>支付系统对接文档</span>
        </el-header>
        <el-container style="margin-top: 60px;">
            <byte-md-viewer :value="content" :plugins="plugins"  style="width: 960; margin:auto;"/>
                <!--
            <el-row>
                <el-col :span="6">
                    <el-aside class="el-aside" style="height: 500px; ">
                        <el-menu
                                default-active="2"
                                class="el-menu-vertical-demo"
                                >
                            <el-menu-item index="1" @click="goTo('create')">
                                <i class="el-icon-menu"></i>
                                <span slot="title">创建订单</span>
                            </el-menu-item>
                            <el-menu-item index="2"  @click="goTo('query')">
                                <i class="el-icon-menu"></i>
                                <span slot="title">订单查询</span>
                            </el-menu-item>
                            <el-menu-item index="3" @click="goTo('signature')">
                                <i class="el-icon-document"></i>
                                <span slot="title">签名算法</span>
                            </el-menu-item>
                            <el-menu-item index="4">
                                <i class="el-icon-setting"></i>
                                <span slot="title">导航四</span>
                            </el-menu-item>
                        </el-menu>
                    </el-aside>
                </el-col>

                <el-col :span="18">
                    <el-main class="el-main">
                    </el-main>
                </el-col>
            </el-row>
                            -->


        </el-container>
    </el-container>

</template>

<script>
    import { Viewer as ByteMdViewer } from '@bytemd/vue';
    // 链接、删除线、复选框、表格等的插件
    import gfm from '@bytemd/plugin-gfm';
    // 内容样式，这里用的 github 的 markdown 主题
    import 'github-markdown-css/github-markdown-light.css';
    // 这里演示请求远程数据，实际项目可以写在 api 中
    import axios from 'axios';

    export default {
        name:"DocIndex",
        components: { ByteMdViewer },
        data() {
            return {
                collapse: false,
                // markdown 内容
                content: '',
                // 插件
                plugins: [gfm()],
            };
        },
        created() {
            // 获取远程数据
            axios.get('/doc.md').then((res) => {
                console.log(res);
                this.content = res.data;
            });
        },
        methods:{
            goTo(fd){
                console.log('goto');
                window.location.href = '/doc#user-content-' + fd;
            }
        }

    };
</script>

<style>
/*铺满屏幕，没有边距*/
.el-container {
    padding: 0px;
    margin: 0px;
    height: 100wh;
}   
.el-header {
    position: absolute;
    line-height: 50px;
    top: 0px;
    left: 0px;
    right: 0px;
    background-color: #2d3a4b;
    color: #DDD;
}
.el-aside {
    width: 200px;
    overflow-y: auto; /* 当内容过多时y轴出现滚动条 */
    background-color: #545c64;
}

</style>

