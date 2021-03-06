<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"F:\php\wuif1808\shop\public/../application/admin\view\user\index.html";i:1544092161;s:62:"F:\php\wuif1808\shop\application\admin\view\common\header.html";i:1543480102;}*/ ?>
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
        <el-breadcrumb-item>用户管理</el-breadcrumb-item>
        <el-breadcrumb-item>查看用户</el-breadcrumb-item>
    </el-breadcrumb>
    <el-tabs v-model="activeName"   style="margin-top: 30px">
        <el-tab-pane label="查看用户" name="show">
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
                label="用户ID"
                prop="id"
                align="center"
                >
                </el-table-column>
                <el-table-column
                        label="用户名"
                        align="center"
                >
                    <template slot-scope="scope">
                        <span v-html="Glusername(scope.row.username)"></span>
                    </template>
                </el-table-column>
                <el-table-column
                        label="用户昵称"
                        prop="nickname"
                        align="center"
                >
                </el-table-column>
                <el-table-column
                        label="用户电话"
                        prop="telephone"
                        align="center"
                >
                </el-table-column>
                <el-table-column
                        label="用户头像"
                >
                    <template slot-scope="scope" >
                        <img :src="scope.row.headimg?scope.row.headimg:'/uploads/user/default.png'" style="width: 100px;height: 100px;" alt="">
                    </template>
                </el-table-column>
                <el-table-column
                        label="注册时间"
                >
                    <template slot-scope="scope">
                        {{scope.row.time | GSHtime}}
                    </template>

                </el-table-column>
                <el-table-column
                        label="用户状态"
                        prop="status"
                        align="center"
                >
                    <template slot-scope="scope">
                        <template v-if="scope.row.status">
                            <!--{{scope.row}}  1-->
                            <el-button type="danger" @click="changeStatus(scope.row.id,scope.row.status)">黑名单</el-button>
                        </template>
                        <template v-else="scope.row.status">
                            <!--{{scope.row.status}}  0-->
                            <el-button type="success" @click="changeStatus(scope.row.id,scope.row.status)">白名单</el-button>
                        </template>
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
            size:5,
            //总数据
            tot:1000,
            //当前页码
            page:1,
            //搜索关键字
            search:"",
            loading:true
        },
        //过滤
        filters:{
          GSHtime(val){
              // console.log(val); //对应的时间里面写的数字
              //时间对象
              date=new Date(val*1000);
              //返回格式化的时间
              return `${date.getFullYear()}-${date.getMonth()}-${date.getDate()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`
          }
        },
        //事件
        methods:{
            //获取用户数据
            getUserData(){
                this.loading=true;
                //需要请求的数据 page 页码 search 当前搜索的内容
                //接口需要返回的数据
                    //1.当前页码的数据
                    //2.每页显示个数
                    //3.总数据
                let data={params:{page:this.page,search:this.search}};
                axios.get("<?php echo url('user/getuserdata'); ?>",data)
                    .then(response=>{
                            //设置数据
                            this.tableData=response.data.data;
                            this.size=response.data.size;
                            this.tot=response.data.tot;
                            this.loading=false;
                            //提示信息
                        this.$message({
                            type:"success",
                            message:"获取成功"
                        });
                    });
            },
            //页面改变方法
            pageChange(val){
                // alert(val); //点击对应的页数输出对应页码
                //设置当前页码
                this.page=val;
                //重新获取数据
                this.getUserData();
            },
            //页面搜索
            searchChange(){
                this.page=1;  //初始化搜索页面
                this.getUserData(); //重新获取数据
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
            //改变用户状态
            changeStatus(id,status){
                // alert(id);
                // alert(status); //对应的id跟状态
                status=status==1?0:1;
                //发送请求修改数据
                axios.post('<?php echo url("user/savestatus"); ?>',{id:id,status:status})
                    .then(response=>{
                        //判断是否修改成功
                        if(response.data==1){
                            this.$message({
                                type:"success",
                                message:"修改用户状态成功"
                            });
                            this.getUserData();
                        }else{
                            this.$message({
                                type:"error",
                                message:"修改用户状态失败"
                            });

                        }
                    })
                    .catch();
            },
        },

        //挂载完毕
        mounted(){
            this.getUserData();
        }
})
</script>
</html>