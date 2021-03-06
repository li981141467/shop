<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"F:\php\wuif1808\shop\public/../application/admin\view\orders\index.html";i:1545872710;s:62:"F:\php\wuif1808\shop\application\admin\view\common\header.html";i:1543480102;}*/ ?>
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
        <el-breadcrumb-item>订单管理</el-breadcrumb-item>
    </el-breadcrumb>
    <el-tabs v-model="activeName"   style="margin-top: 30px">
        <el-tab-pane label="查看订单" name="show">
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
                label="订单号"
                prop="code"
                align="center"
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
                        label="订单总额"
                        prop="money"
                        align="center"
                >
                    <template slot-scope="scope">
                        {{scope.row.money.toFixed(2)}}
                    </template>
                </el-table-column>
                <el-table-column
                        label="优惠金额"
                        align="center"
                        width="50px"
                >
                    <template slot-scope="scope">
                        {{scope.row.discount_money.toFixed(2)}}
                    </template>
                </el-table-column>
                <el-table-column
                        label="支付金额"
                        align="center"
                        prop="text"
                >
                    <template slot-scope="scope">
                        {{scope.row.pay_money.toFixed(2)}}
                    </template>
                </el-table-column>
                <el-table-column
                        label="订单创建时间"
                        align="center"
                        prop="create_time"
                        width="100px"
                >
                </el-table-column>
                <el-table-column
                        label="订单状态"
                        align="center"

                >
                    <template slot-scope="scope">
                        <el-select v-model="scope.row.status" placeholder="请选择状态信息" @change="changeStatus(scope.row.id,scope.row.status)">
                            <template v-for="item in options">
                                <template v-if="scope.row.status < item.status">
                                    <el-option
                                            :key="item.value"
                                            :value="item.status"
                                            :label="item.name"
                                           >
                                    </el-option>
                                </template>
                                <template v-else>
                                    <el-option
                                            :key="item.value"
                                            :value="item.status"
                                            :label="item.name"
                                            disabled
                                    >
                                    </el-option>
                                </template>
                            </template>

                        </el-select>
                    </template>
                </el-table-column>
                <el-table-column
                        label="操作"
                        align="center"
                >
                    <template slot-scope="scope">
                        <el-button type="success" @click="showOrders(scope.row.id)">订单详情</el-button>
                        <br>
                        <el-button type="danger" @click="showAddr(scope.row)">收货地址</el-button>
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


    <el-dialog title="收货地址" :visible.sync="showShouBox" width="100%">
        <table style="width: 100%;height: 100%;border-collapse:collapse" border="1px" >
            <tr align="center">
                <th>收货人</th>
                <th>收货电话</th>
                <th>收货地址</th>
            </tr>
            <tr align="center">
                <td>{{shouData.shou_name}}</td>
                <td>{{shouData.shou_phone}}</td>
                <td>{{shouData.shou_addr}}</td>
            </tr>
        </table>
    </el-dialog>

    <el-dialog title="订单详情" :visible.sync="showOrderBox" width="100%">
        <el-table
                :data="orderData"
        >
            <el-table-column
                    label="ID"
                    prop="id"
                    align="center"
            >
            </el-table-column>
            <el-table-column
                    label="商品名"
                    prop="goods_name"
                    align="center"
            >
            </el-table-column>
            <el-table-column
                    label="商品图片"
                    align="center"
            >
                <template slot-scope="scope">
                    <img :src="scope.row.goods_img" alt="" style="width: 100px;height: 100px">
                </template>
            </el-table-column>
            <el-table-column
                    label="商品价格"
                    align="center"
            >
                <template slot-scope="scope">
                    {{scope.row.goods_price.toFixed(2)}}
                </template>
            </el-table-column>
            <el-table-column
                    label="商品数量"
                    align="center"
                    prop="num"
            >
            </el-table-column>
        </el-table>
    </el-dialog>

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
            loading:false,
            //状态
            options:[
                {name:"未付款",status:0},
                {name:"已付款",status:1},
                {name:"待发货",status:2},
                {name:"已发货",status:3},
                {name:"在途中",status:4},
                {name:"已收货",status:5},
                {name:"评价中",status:6},
            ],
            //收货表格
            showShouBox:false,
            //收货数据
            shouData:{
                shou_name:"",
                shou_phone:"",
                shou_addr:"",
            },
            //详情页
            showOrderBox:false,
            //详情表
            orderData:[],
        },
        //事件
        methods:{
            //改变订单状态
            changeStatus(id,status){
              axios.post("/admin/orders/savestatus",{id:id,status:status})
                  .then(response=>{
                      if(response.data==1){
                          this.$notify({
                              type:"success",
                              message:"修改成功"
                          });
                      }else{
                          this.$notify({
                              type:"error",
                              message:"修改失败"
                          });
                      }
                  })
                  .catch();
            },
            //显示订单详情
            showOrders(id){
              this.showOrderBox=true;
              //发送数据
                axios.get("/admin/orders/orderlist?id="+id)
                    .then(response=>{
                       this.orderData=response.data;
                    })
                    .catch();
            },
            //显示收货地址
            showAddr(obj){
                //展示对话框
              this.showShouBox=true;
              this.shouData.shou_name=obj.shou_name;
              this.shouData.shou_phone=obj.shou_phone;
              this.shouData.shou_addr=obj.shou_addr;
              // console.log(obj);
            },
            //获取订单列表
            getOrderData(){
            axios.get(`/admin/orders/getorderdata?page=${this.page}&search=${this.search}`)
                .then(response=>{
                    this.tableData=response.data.data;
                    this.tot=response.data.tot;
                    this.size=response.data.size;
                    this.$notify({
                        type:"success",
                        message:"获取成功"
                    })
                })
                .catch();
            },
            //页面改变方法
            pageChange(val){
                // alert(val); //点击对应的页数输出对应页码
                //设置当前页码
                this.page=val;
                //重新获取数据
                this.getOrderData();
            },
            //页面搜索
            searchChange(){
                this.page=1;  //初始化搜索页面
                this.getOrderData(); //重新获取数据
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
        },

        //挂载完毕
        mounted(){
            this.getOrderData();
        }
})
</script>
</html>