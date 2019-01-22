<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"F:\php\wuif1808\shop\public/../application/admin\view\system\index.html";i:1544427072;s:62:"F:\php\wuif1808\shop\application\admin\view\common\header.html";i:1543480102;}*/ ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>admin</title>
    <link rel="stylesheet" href="/static/css/element-ui.css">
    <script src="/static/js/vue.js"></script>
    <script src="/static/js/element-ui.js"></script>
    <script src="/static/js/axios.js"></script>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html,body,#app{
            width: 100%;
            height: 100%;
        }
        a{
            color: #333;
            text-decoration: none;
        }
    </style>
</head>

<style>
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }
    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 178px;
        height: 178px;
        line-height: 178px;
        text-align: center;
    }
    .avatar {
        width: 378px;
        height: 178px;
        display: block;
    }
</style>
<body>
<div id="app">
    <!--面包屑导航-->
    <el-breadcrumb separator="/">
        <el-breadcrumb-item>系统管理</el-breadcrumb-item>
        <el-breadcrumb-item>系统管理</el-breadcrumb-item>
    </el-breadcrumb>
    <el-tabs v-model="activeName"   style="margin-top: 30px">
        <el-tab-pane label="查看系统配置信息" name="show">
            <el-form ref="form" v-model="config" label-width="80px">
                <el-form-item label="标题">
                    <el-input placeholder="请输入网站标题" v-model="config.title"></el-input>
                </el-form-item>
                <el-form-item label="关键字">
                    <el-input placeholder="请输入网站关键字" v-model="config.keywords"></el-input>
                </el-form-item>
                <el-form-item label="描述">
                    <el-input placeholder="请输入网站描述" v-model="config.description"></el-input>
                </el-form-item>
                <el-form-item label="网站logo">
                    <el-upload
                        class="avatar-uploader"
                        action="/admin/upload/upload?type=tmp"
                        :show-file-list="false"
                        :on-success="success"
                >
                    <img v-if="config.logo" :src="config.logo" class="avatar">
                    <!--这里的config.log是"/config/logo.png"-->
                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                </el-upload>
                </el-form-item>
                <el-form-item label="版权信息">
                    <el-input placeholder="请输入网站版权信息" v-model="config.copyright"></el-input>
                </el-form-item>
                <el-form-item label="备案信息">
                    <el-input placeholder="请输入网站备案信息" v-model="config.record"></el-input>
                </el-form-item>
                <el-form-item label="百度统计">
                    <el-input type="textarea" placeholder="请输入网站百度统计代码" v-model="config.count"  rows="5"></el-input>
                </el-form-item>
                <el-button type="success" @click="submitData">提交</el-button>
                <el-button type="danger" @click="resetData">重置</el-button>
            </el-form>
        </el-tab-pane>
</div>
</body>
<script>
    let vue=new Vue({
        el:"#app",
        data:{
            from:{},
            activeName:"show",
            config:{},
            url:"<?php echo url('admin/system/system'); ?>"
        },
        methods:{
            //获取数据方法
            getConfigData(){
                //发送请求获取数据
                axios.get(this.url)
                    //响应正确的结果
                    .then(response=>{
                        //设置数据
                    this.config=response.data;
                    //把老图片放到新图片
                    this.config.oldLogo=response.data.logo;
                    this.$message({
                        type:"success",
                        message:"获取数据成功"
                    });
                }).catch(error=>{
                    this.$message({
                        type:"error",
                        message:"获取数据失败"
                    });
                })
            },//图片上传成功处理操作
            success(val){
                // console.log();
                this.config.logo=val;
            },
            //重置数据
            resetData(){
                this.getConfigData();
            },
            //提交大数据
            submitData(){
                //发送请求
                axios.post(this.url,this.config)
                    .then(response=>{
                        //判断是否成功
                        if(response.data.code==200){
                            //更新数据
                            this.getConfigData();
                            this.$message({
                                "type":"success",
                                "message":response.data.info
                            })
                        }else{
                            this.$message({
                                "type":"error",
                                "message":response.data.info
                            })
                        }
                    })
                    .catch(error=>{
                        this.$message({
                            "type":"error",
                            "message":"未知错误"
                        })
                    });
            },
        },

        //挂载结束
        mounted(){
            this.getConfigData();
        }
    });
    // console.log((vue.url));
</script>
