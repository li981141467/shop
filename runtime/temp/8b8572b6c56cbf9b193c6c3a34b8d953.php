<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:72:"F:\php\wuif1808\shop\public/../application/admin\view\comment\index.html";i:1544667080;s:62:"F:\php\wuif1808\shop\application\admin\view\common\header.html";i:1543480102;}*/ ?>
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
    <el-breadcrumb separator="/">
        <el-breadcrumb-item>商品管理</el-breadcrumb-item>
        <el-breadcrumb-item>评论管理</el-breadcrumb-item>
    </el-breadcrumb>
    <el-tabs v-model="activeName"   style="margin-top: 30px">
        <el-tab-pane label="查看评论" name="show">
            <el-row>
                <el-col :span="6">
                </el-col>
                <el-col :span="12">
                    <el-input  v-model="search" @change="searchChange" placeholder="请输入搜索内容"></el-input>
                </el-col>
            </el-row>
            <el-table
            :data="tableData"
            v-loading="loading"
            >
                <el-table-column
                label="ID"
                prop="id"
                align="center"
                width="50px"
                >
                </el-table-column>
                <el-table-column
                        label="用户名"
                        align="center"
                        width="100px"
                >
                    <template slot-scope="scope">
                        <span v-html="Glusername(scope.row.username)"></span>
                    </template>
                </el-table-column>
                <el-table-column
                        label="商品名"
                        prop="short_name"
                        align="center"
                >
                </el-table-column>
                <el-table-column
                        label="商品图片"
                        align="center"
                >
                    <template slot-scope="scope">
                        <img :src="scope.row.cover_img" alt="" style="width: 100px;height: 100px">
                    </template>
                </el-table-column>
                <el-table-column
                        label="评论内容"
                        align="center"
                        prop="text"
                >

                </el-table-column>
                <el-table-column
                        label="评论星级"
                        align="center"
                        prop="star"
                        width="150px"
                >
                <template slot-scope="scope">
                    <el-rate v-model="scope.row.star"  :colors="['#99A9BF', '#F7BA2A', '#FF9900']" show-text  show-score disabled></el-rate>
                </template>
                </el-table-column>
                <el-table-column
                        label="评论状态"
                        align="center"

                >
                    <!--0未审核，1,审核通过，2，审核失败-->
                    <template slot-scope="scope">
                        <el-select v-model="scope.row.status" placeholder="请选择状态信息" @change="changeStatus(scope.row.id,scope.row.status)">
                            <el-option
                                    :key="item.value"
                                    v-for="item in options"
                                    :value="item.status"
                                    :label="item.name"
                                   >
                            </el-option>
                        </el-select>
                    </template>
                </el-table-column>
            </el-table>
            <div style="text-align: center;margin: 10px 0px">
                <el-pagination
                        background
                        :page-size="size"
                        layout="prev, pager, next"
                        :total="tot"
                        @current-change="pageChange"
                >
                </el-pagination>
            </div>
        </el-tab-pane>
    </el-tabs>
</div>
</body>
<script>
    let vue=new Vue({
        el:"#app",
        data: {
            //默认显示标签页
            activeName:'show',
            tableData:[],
            //每页显示的个数
            size:0,
            //总数据
            tot:0,
            //当前页码
            page:1,
            //搜索关键字
            search:"",
            loading:true,
            //状态
            options:[
                {name:"未审核",status:0},
                {name:"审核通过",status:1},
                {name:"审核失败",status:2},
            ]
        },
        //事件
        methods:{
            //获取用户数据
            getCommentData(){
                //发送请求获取评论数据
                this.loading=true;
                //需要请求的数据 page 页码 search 当前搜索的内容
                //接口需要返回的数据
                //1.当前页码的数据
                //2.每页显示个数
                //3.总数据
                axios.get(`/admin/comment/getdata?page=${this.page}&search=${this.search}`)
                    .then(response=>{

                        this.loading=false;
                        this.tableData=response.data.data;
                        this.size=response.data.size;
                        this.tot=response.data.tot;
                        // alert(this.tableData);
                        // console.log(this.tableData);
                        this.$notify({
                            type:"success",
                            message:"获取数据成功"
                        });
                    })
                    .catch();
            },
            //页面改变方法
            pageChange(val){
                // alert(val); //点击对应的页数输出对应页码
                //设置当前页码
                this.page=val;
                //重新获取数据
                this.getCommentData();
            },
            //页面搜索
            searchChange(){
                this.page=1;  //初始化搜索页面
                this.getCommentData(); //重新获取数据
            },
            //过滤用户名 搜索中变红
            Glusername(val){
                // console.log(vue.search); //搜索框输啥就是啥
                if(!vue.search){
                    return val;
                }else{
                    return val.replace(new RegExp(vue.search,'i'),'<b style="color:red;">'+vue.search+"</b>");
                    //i不区分大小写
                }
            },
            //改变状态
            changeStatus(id,status){
                // alert(id);  //对应的id
                // alert(status); //对应的status
                axios.post("/admin/comment/savestatus",{id:id,status:status})
                    .then(response=>{
                        if(response.data==1){
                            this.$notify({
                                type:"success",
                                message:"修改成功"
                            })
                        }else{
                            this.$notify({
                                type:"error",
                                message:"修改失败"
                            })
                        }
                    })
                    .catch();
            }
        },

        //挂载完毕
        mounted(){
            this.getCommentData();
        }
})
</script>
</html>