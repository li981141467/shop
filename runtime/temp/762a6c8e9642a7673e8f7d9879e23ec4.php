<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:70:"F:\php\wuif1808\shop\public/../application/admin\view\admin\admin.html";i:1544672328;s:62:"F:\php\wuif1808\shop\application\admin\view\common\header.html";i:1543480102;}*/ ?>
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
    .header{
        width: 100%;
        padding: 0px 10px;
        box-sizing: border-box;
        height: 70px;
        background: #cccccc;
    }
    .header-left{
        float:left;
        margin-top: 5px;
        height: 60px;
        color: white;
        font-size: 30px;
        text-align: center;
        line-height: 60px;
        margin-left: 150px;
    }
    .header-right{
        float: right;
        display: flex;
        line-height: 60px;
        width: 250px;
        justify-content: space-between;
        color: #fff;
        font-size: 25px;
        margin-right: 150px;
        margin-top: 5px;
    }
    .header-right a{
        color:brown ;
    }
</style>
<body>
<div id="app">
    <el-row class="header">
        <div class="header-left">优逸客商城后台管理系统</div>
        <div class="header-right">
            <div>欢迎<?php echo session("uekshop_message_username"); ?>登录</div>
            <div><a href="<?php echo url('login/logout'); ?>">退出</a></div>
        </div>
    </el-row>
    <el-container style="height: 100%;">
        <el-row style="width: 100%;height: 100%;">
            <el-col :offset="3" :span="18" style="height: 100%;">
                <el-container style="height: 100%;">
                    <el-aside width="200px">
                        <el-menu :default-openeds="['system','user','goods','count']">
                            <el-submenu index="system">
                                <template slot="title">
                                    <i class="el-icon-setting"></i>
                                    <span>系统管理</span>
                                </template>
                                <el-menu-item index="1-1"><a href="<?php echo url('admin/system/editpass'); ?>" target="main">密码修改</a></el-menu-item>
                                <el-menu-item index="1-2"><a href="<?php echo url('admin/banner/index'); ?>" target="main">轮播管理</a></el-menu-item>
                                <el-menu-item index="1-3"><a href="<?php echo url('admin/system/index'); ?>" target="main">系统管理</a></el-menu-item>
                            </el-submenu>
                            <el-submenu index="user">
                                <template slot="title">
                                    <i class="el-icon-setting"></i>
                                    <span>用户管理</span>
                                </template>
                                <el-menu-item index="2-4"><a href="<?php echo url('Message/index'); ?>" target="main">管理员管理</a></el-menu-item>
                                <el-menu-item index="2-1"><a href="<?php echo url('User/index'); ?>" target="main">查看用户</a></el-menu-item>
                            </el-submenu>
                            <el-submenu index="goods">
                                <template slot="title">
                                    <i class="el-icon-setting"></i>
                                    <span>商品管理</span>
                                </template>
                                <el-menu-item index="3-1"><a href="<?php echo url('Goods/index'); ?>" target="main">商品管理</a></el-menu-item>
                                <el-menu-item index="3-2"><a href="<?php echo url('Type/index'); ?>" target="main">分类管理</a></el-menu-item>
                                <el-menu-item index="3-3"><a href="<?php echo url('Comment/index'); ?>" target="main">评论管理</a></el-menu-item>
                                <el-menu-item index="3-4"><a href="<?php echo url('Orders/index'); ?>" target="main">订单管理</a></el-menu-item>
                            </el-submenu>
                            <el-submenu index="count">
                                <template slot="title">
                                    <i class="el-icon-menu"></i>
                                    <span>综合统计</span>
                                </template>
                                <el-menu-item index="4-1">销量统计</el-menu-item>
                                <el-menu-item index="4-2">商品统计</el-menu-item>
                            </el-submenu>
                        </el-menu>
                    </el-aside>
                    <el-main style="height: 100%;overflow: hidden" >
                        <iframe src="<?php echo url('admin/banner/index'); ?>" frameborder="0" style="width: 100%;height: 100%;" name="main"></iframe>
                    </el-main>
                </el-container>
            </el-col>
        </el-row>
    </el-container>
</div>
</body>
<script>
    new Vue({
        el:"#app",
    })
</script>
</html>