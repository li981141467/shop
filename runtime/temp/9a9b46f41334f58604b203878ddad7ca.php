<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:74:"F:\php\wuif1808\shop\public/../application/admin\view\system\editpass.html";i:1543971154;s:62:"F:\php\wuif1808\shop\application\admin\view\common\header.html";i:1543480102;}*/ ?>
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

<body>
<div id="app">
    <!--面包屑导航-->
    <el-breadcrumb separator="/">
        <el-breadcrumb-item>系统管理</el-breadcrumb-item>
        <el-breadcrumb-item>密码修改</el-breadcrumb-item>
    </el-breadcrumb>
    <el-tabs v-model="activeName"   style="margin-top: 30px">
        <el-tab-pane label="修改密码" name="show">
            <el-form ref="form" v-model="userData" label-width="80px">
                <el-form-item label="用户名">
                    <el-input placeholder="请输入用户名" v-model="userData.username" readOnly></el-input>
                </el-form-item>
                <el-form-item label="密码">
                    <el-input placeholder="请输入密码" v-model="pass"></el-input>
                </el-form-item>
                <el-form-item label="确认密码">
                    <el-input placeholder="请输入确认密码" v-model="repass"></el-input>
                </el-form-item>
                <el-form-item label="状态">
                    <template>
                        <el-radio v-model="userData.status" label="0">白名单</el-radio>
                        <el-radio v-model="userData.status" label="1">黑名单</el-radio>
                    </template>
                </el-form-item>
                <el-button type="success" @click="submitData" >提交</el-button>
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
            userData:{},
            pass:"",
            repass:"",
            url:"<?php echo url('admin/system/pass'); ?>"
        },
        methods:{
            //获取需要修改的数据
            getUserData(){
                axios.get(this.url)
                    .then(response=>{
                        this.pass="";
                        this.repass="";
                        // console.log(response.data);  //获取响应的数据 是一个对象
                        this.userData=response.data;
                        this.userData.status=this.userData.status+""; //把它的状态由数字转换成字符串
                        // console.log(this.userData);  //同上一个输出
                        //network 里面会有一个响应的pass.html的输出 里面就是这个对象
                        this.$message({
                            type:"success",
                            message:"获取成功"
                        })
                    })
                    .catch(error=>{
                        this.$message({
                            type:"error",
                            message:"获取失败"
                        })
                    })
            },
            //提交数据方法
            submitData(){
                //判断用户是否修改密码
                if(this.pass || this.repass){
                    //判断密码是否一致
                    if(this.pass==this.repass){
                        //格式化提交的数据
                         post={
                            password:this.pass,
                            id:this.userData.id,
                            status:this.userData.status,
                        }
                    }else{
                        this.$message({
                            type:"error",
                            message:"两次密码不一致    "
                        });
                        return "";
                    }
                }else{
                    //没有修改密码时，只修改黑白名单时候的数据
                    post={
                        id:this.userData.id,
                        status:this.userData.status,
                    }
                };
                // console.log(post);

                //提交数据
                axios.post(this.url,post)
                    .then(response=>{
                        //判断是否修改成功
                        if(response.data==1){
                            this.$message({
                                type:"success",
                                message:"修改成功"
                            });
                        }else{
                            this.$message({
                                type:"error",
                                message:"修改失败"
                            });
                        }
                        this.getUserData();
                    })
                    .catch();
            },
            //重置
            resetData(){
                this.getUserData();
            }
        },
        //挂载结束
        mounted(){
            this.getUserData();
        }
    });
</script>
