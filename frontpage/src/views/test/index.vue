<template>
    <div class="container" style="width: 600px; margin: auto;">
        <div class="ele-page-header">
            <div class="ele-page-title">测试</div>
            <div class="ele-page-desc">
                通道测试
            </div>
        </div>
        <div class="ele-body">
            <el-card shadow="never">
                <el-form
                        ref="form"
                        :model="form"
                        :rules="rules"
                        label-width="90px"
                        style="max-width: 700px; margin: 10px auto"
                        >
                        <el-form-item label="通道:" prop="channel_id">
                            <channel-select v-model="form.channel_id"></channel-select>
                        </el-form-item>
                        <el-form-item label="金额:" prop="amount">
                            <el-input v-model="form.amount" placeholder="请输入金额" clearable />
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" :loading="loading" @click="submit">
                                提交
                            </el-button>
                        </el-form-item>
                </el-form>
            </el-card>
        </div>
    </div>
</template>

<script>
    import * as api from '@/api/admin';
    export default {
        name: 'testIndex',
        data() {
            return {
                // 提交状态
                loading: false,
                // 表单数据
                form: {
                    amount: 100,
                    channel_id: 1
                },
                // 表单验证规则
                rules: {
                    amount: [
                        {
                            required: true,
                            message: '请输入金额',
                            trigger: 'blur'
                        }
                    ],
                    channel_id: [
                        {
                            required: true,
                            message: '请选择通道',
                            trigger: 'blur'
                        }
                    ],
                },
            };
        },
        methods: {
            /* 提交 */
            submit() {
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        this.loading = true;

                        api.create_test(this.form)
                            .then((res) => {
                                console.log(res);
                                window.open(res);
                                this.loading = false;
                            })
                            .catch((e) => {
                                this.loading = false;
                                this.$message.error(e);
                                //callback();
                            });

                    } else {
                        return false;
                    }
                });
            },
        }
    };
</script>
