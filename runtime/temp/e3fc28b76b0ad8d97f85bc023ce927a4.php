<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:70:"F:\php\wuif1808\shop\public/../application/admin\view\goods\index.html";i:1544600746;s:62:"F:\php\wuif1808\shop\application\admin\view\common\header.html";i:1543480102;}*/ ?>
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
        position: relative;
        overflow: hidden;
        display: flex;
        flex-wrap: wrap;
    }
    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }
    .avatar-uploader-icon {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;

        font-size: 28px;
        color: #8c939d;
        width: 178px;
        height: 178px;
        line-height: 178px;
        text-align: center;
    }
    .avatar {
        width: 178px;
        height: 178px;
        display: block;
    }
    .imgss{
        position: relative;
    }
    .close{
        position: absolute;
        right: 0;
        top: 0;
        border-radius: 10px;
        width: 20px;
        height: 20px;
        text-align: center;
        line-height: 20px;
        color: whitesmoke;
        background: black;
    }
    .edui-default{
        line-height: 20px;
    }
</style>
<script type="text/javascript" charset="utf-8" src="/static/baidu/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/static/baidu/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/static/baidu/lang/zh-cn/zh-cn.js"></script>
<body>
<div id="app">
    <el-breadcrumb separator="/">
        <el-breadcrumb-item>商品管理</el-breadcrumb-item>
        <el-breadcrumb-item>商品管理</el-breadcrumb-item>
    </el-breadcrumb>
    <el-tabs v-model="activeName"   style="margin-top: 30px">
        <el-tab-pane label="查看商品" name="show">
            <el-row>
                <el-col :span="6">
                </el-col>
                <el-col :span="12">
                    <el-input v-model="search" @change="searchChange" placeholder="请输入搜索内容"></el-input>
                </el-col>
            </el-row>
            <el-table

                    :data="tableData"
                    style="width: 100%"
                    v-loading="loading"
            >

                <el-table-column
                        prop="id"
                        label="ID"
                        width="100px"
                        align="center"
                >
                </el-table-column>
                <el-table-column
                        label="商品名称"
                        align="center"
                >
                    <template slot-scope="scope">
                        <span v-html="Glusername(scope.row.name)"></span>
                        <br>
                        <span v-html="Glusername(scope.row.short_name)"></span>
                    </template>
                </el-table-column>
                <el-table-column
                        label="商品图片"
                        align="center"
                >
                    <template slot-scope="scope">
                        <img style="width: 100px;height: 100px" :src="scope.row.cover_img" alt="">
                    </template>
                </el-table-column>
                <el-table-column
                        label="商品分类"
                        align="center"
                >
                    <template slot-scope="scope">
                        <span>{{scope.row.tname}}</span>
                    </template>
                </el-table-column>
                <el-table-column
                        prop="username"
                        label="商品价格"
                        align="center"
                >
                    <template slot-scope="scope">
                        <del>{{scope.row.price}}</del>
                        <br>
                        <span>{{scope.row.now_price}}</span>
                    </template>
                </el-table-column>
                <el-table-column
                        prop="status"
                        label="上下架"
                        align="center"
                >
                    <template slot-scope="scope">
                        <template v-if="scope.row.is_shelf">
                            <!--{{scope.row}}  1-->
                            <el-button type="danger" @click="changeStatus(scope.row.id,scope.row.is_shelf)">下架</el-button>
                        </template>
                        <template v-else="scope.row.is_shelf">
                            <!--{{scope.row.status}}  0-->
                            <el-button type="success" @click="changeStatus(scope.row.id,scope.row.is_shelf)">上架</el-button>
                        </template>
                    </template>
                </el-table-column>
                <el-table-column
                        label="库存数量"
                        align="center"
                >
                    <template slot-scope="scope">
                        <span>{{scope.row.stock}}</span>
                    </template>
                </el-table-column>
                <el-table-column
                        label="创建时间"
                        align="center"
                >
                    <template slot-scope="scope">
                        <span>{{scope.row.create_time}}</span>
                    </template>
                </el-table-column>
                <el-table-column
                        prop=""
                        label="操作"
                        align="center"
                >
                    <template slot-scope="scope">
                        <!--<el-button type="success" @click="saveData1(scope.row)">修改</el-button>-->
                        <a :href="'/admin/goods/edit?id='+scope.row.id">
                            <el-button type="success">修改</el-button>
                        </a>
                        <el-button type="danger" @click="del(scope.row.id)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div style="text-align: center;margin: 10px 0px">
                <template v-if="tableData.length">
                    <el-pagination
                            @current-change="pageChange"
                            background
                            :page-size="size"
                            layout="prev, pager, next"
                            :total="tot">
                    </el-pagination>
                </template>
            </div>
        </el-tab-pane>
        <el-tab-pane label="添加商品" name="add">
            <el-form :model="formData" :rules="rules" ref="add" label-width="100px">
                <el-form-item  label="短商品名" prop="short_name">
                    <el-input  v-model="formData.short_name" placeholder="请输入短商品名"></el-input>
                </el-form-item>
                <el-form-item label="商品名" prop="name">
                      <el-input v-model="formData.name" placeholder="请输入商品名"></el-input>
                 </el-form-item>
                <el-form-item label="商品分类" prop="type_id" >
                    <el-cascader
                            ref="select"
                            :options="options"
                            :props="props"
                            @change="changeType"
                            :clearable="clearable"
                    ></el-cascader>
                </el-form-item>
                <el-form-item label="促销信息" prop="info">
                    <el-input v-model="formData.info" placeholder="请输入促销信息"></el-input>
                </el-form-item>
                <el-form-item label="关键字" prop="keywords">
                    <el-input  v-model="formData.keywords" placeholder="请输入关键字"></el-input>
                </el-form-item>
                <el-form-item label="商品描述" prop="description">
                    <el-input v-model="formData.description" placeholder="请输入商品描述"></el-input>
                </el-form-item>
                <el-form-item label="原价" prop="price">
                    <el-input v-model="formData.price" type="number" placeholder="请输入商品原价"></el-input>
                </el-form-item>
                <el-form-item label="现价" prop="now_price">
                    <el-input v-model="formData.now_price" type="number" placeholder="请输入商品现价"></el-input>
                </el-form-item>
                <el-form-item label="库存" prop="stock">
                    <el-input v-model="formData.stock" type="number" placeholder="请输入商品库存量"></el-input>
                </el-form-item>

                <el-form-item label="上下架" prop="is_shelf">
                    <!--<template>-->
                        <el-radio v-model="formData.is_shelf" label="0">上架</el-radio>
                        <el-radio v-model="formData.is_shelf" label="1">下架</el-radio>
                    <!--</template>-->
                </el-form-item>
                <el-form-item label="商品封面图" prop="cover_img">
                        <el-upload
                                class="avatar-uploader"
                                action="/admin/upload/upload?type=tmp"
                                :show-file-list="false"
                                :on-success="success"
                        >
                                <img v-if="formData.cover_img" :src="formData.cover_img" class="avatar">
                                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                </el-form-item>
                <el-form-item label="商品更多图" prop="imgs">
                    <el-upload
                            class="avatar-uploader"
                            action="/admin/upload/upload?type=tmp"
                            :show-file-list="false"
                            :on-success="successAll"
                    >
                        <template v-for="item in formData.imgs" >
                            <div class="imgss">
                                <div class="close" @click.stop="delImg(item)">&times;</div>
                                <img  :src="item" class="avatar">
                            </div>
                        </template>
                        <i  class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item label="商品详情" prop="text">
                    <div id="editor"  type="text/plain" style="width:100%;height:400px;"></div>
                </el-form-item>
                <!--<el-form-item label="用户状态" prop="status">-->
                    <!--&lt;!&ndash;<template>&ndash;&gt;-->
                        <!--<el-radio  label="0">白名单</el-radio>-->
                        <!--<el-radio  label="1">黑名单</el-radio>-->
                    <!--&lt;!&ndash;</template>&ndash;&gt;-->
                <!--</el-form-item>-->
                <el-button type="success" @click="submitData">提交</el-button>
                <el-button type="danger" @click="resetData">重置</el-button>
            </el-form>
        </el-tab-pane>
    </el-tabs>
</div>
</body>
<script>
    var ue = UE.getEditor('editor');
    let vue=new Vue({
        el:"#app",
        data: {
            activeName: "show",
            //级联选择器数据
            options:[],
            //级联选择器默认值
            props:{
                value:"id",
                label:"name",
                children:"children"
            },
            //初始化表单数据
            formData:{
                short_name:"",
                name:"",
                type_id:"",
                info:"",
                keywords:"",
                description:"",
                price:"",
                now_price:"",
                stock:"",
                is_shelf:"0",
                cover_img:"",
                imgs:[],
                text:"",
            },
            //表单验证规则
            rules:{
                short_name:[
                    { required: true, message: '请输入商品短名称', trigger: 'blur' },
                ],
                name:[
                    { required: true, message: '请输入商品名称', trigger: 'blur' },
                ],
                keywords:[
                    { required: true, message: '请输入商品关键字', trigger: 'blur' },
                ],
                description:[
                    { required: true, message: '请输入商品描述', trigger: 'blur' },
                ],
                price:[
                    { required: true, message: '请输入商品原价', trigger: 'blur' },
                ],
                now_price:[
                    { required: true, message: '请输入商品现价', trigger: 'blur' },
                ],
                stock:[
                    { required: true, message: '请输入商品库存', trigger: 'blur' },
                ],
                cover_img:[
                    { required: true, message: '请选择上传封面图片', trigger: 'blur' },
                ],
                imgs:[
                    { required: true, message: '请选择上传更多图片', trigger: 'blur' },
                ],
                type_id:[
                    { required: true, message: '请选择商品分类', trigger: 'change' },
                ],

            },
            //清空级联,会出现一个小x，点击即可清空
            clearable:true,
            //初始化表格
            tableData:[],
            //舒适化搜索
            search:"",
            //每页展示的数据多少
            size:0,
            //总页数
            tot:0,
            //当前页数
            p:1,
            //loading
            loading:true,
            },
        //方法
        methods:{
            //获取分类数据
            getTypeData(){
                //发送请求
                axios.get("/admin/type/getdata")
                    //监听数据
                    .then(response=>{
                    // console.log(response.data);
                    //设置初始化数据
                    this.options=response.data;
                }).catch(error=>{
                    this.$notify({
                        type:"error",
                        message:"未知错误"
                    })
                });
            },
            //上传单图
            success(val){
                //设置封面图片
                this.formData.cover_img=val;
            },
            successAll(val){
                //追加到数据中
                this.formData.imgs.push(val);
            },
            //删除不想要的图片
            delImg(val){
                let arr=this.formData.imgs.filter(item=>{
                    return item!=val;  //返回不等于点击的对应的，再令this.formData.img=arr，即可删除
                });
                this.formData.imgs=arr;
            },
            //提交数据
            submitData(){
                //点击提交时 会进行验证
                this.$refs['add'].validate(valid=>{
                    //判断规则是否验证通过
                    if(valid){
                        //设置文本域的数据
                         this.formData.text=ue.getContent(); //把文本框里的东西赋值给text

                        // console.log(this.formData); //所有信息
                        // console.log(this.formData.text); //文本框里的东西
                        axios.post("/admin/goods/insert",this.formData)
                            .then(response=>{
                                if(response.data){
                                    this.$notify({
                                        type:"success",
                                        message:"添加成功"
                                    });
                                    this.activeName="show";
                                    this.$refs['add'].resetFields();//把内容重置
                                    this.$refs['select'].currentValue=[];//重置级联
                                    this.getGoodsData();
                                    // formData={
                                    //     short_name:"",
                                    //         name:"",
                                    //         type_id:"",
                                    //         info:"",
                                    //         keywords:"",
                                    //         description:"",
                                    //         price:"",
                                    //         now_price:"",
                                    //         stock:"",
                                    //         is_shelf:"0",
                                    //         cover_img:"",
                                    //         imgs:[],
                                    //         text:"",
                                    // }; //老师说有时候清除不了
                                    ue.setContent("");
                                }
                                //提示信息
                            })
                            .catch(error=>{
                                this.$notify({
                                    type:"error",
                                    message:"未知错误"
                                })
                            });
                    }else{
                        //
                        return false;
                    }
                });
            },
            //重置数据
            resetData(){
                //重置表单的提示框
                this.$refs['add'].resetFields();
                //文本编辑器里的清空
                ue.setContent("");
                //级联清空
                this.$refs['select'].currentValue=[];//重置级联
            },
            //商品分类的change
            changeType(val){
                // console.log(val);  //是一个数组

                this.formData.type_id=val[val.length-1];
                // console.log(val[val.length-1])  //数组的最后一个
            },
            //获取商品数据
            getGoodsData(){
                this.loading=true;
                //发送请求
                axios.get(`/admin/goods/getgoodsdata?p=${this.p}&search=${this.search}`,)
                    .then(response=>{
                        this.$notify({
                           type:"success",
                           message:"获取数据成功"
                        });
                        this.tableData=response.data.data;
                        this.size=response.data.size;
                        this.tot=response.data.tot;
                        this.loading=false;
                    })
                    .catch();
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
            //改变上下架状态
            changeStatus(id,is_shelf){
                // alert(id);
                // alert(status); //对应的id跟状态
                is_shelf=is_shelf==1?0:1;
                //发送请求修改数据
                axios.post('<?php echo url("goods/saveshelf"); ?>',{id:id,is_shelf:is_shelf})
                    .then(response=>{
                        //判断是否修改成功
                        if(response.data==1){
                            this.$message({
                                type:"success",
                                message:"修改用户状态成功"
                            });
                            this.getGoodsData();
                        }else{
                            this.$message({
                                type:"error",
                                message:"修改用户状态失败"
                            });

                        }
                    })
                    .catch();
            },
            //删除数据
            del(id){
                this.$confirm("此操作将永久删除该文件，是否继续？","提示",{
                    confirmButtonText:'确定',
                    cancelButtonText:'取消',
                    type:'warning'//messagebox 弹框
                })
                    .then(()=>{
                        axios.get("/admin/goods/deldata?id="+id)
                            .then(response=>{
                               if(response.data==1){
                                   this.$message({
                                       type:"success",
                                       message:"删除成功",
                                   });
                                   this.getGoodsData();
                               }else{
                                   this.$message({
                                       type:"error",
                                       message:"删除失败",
                                   });
                               }
                            })
                            .catch(error=>{
                                this.$message({
                                    type:"error",
                                    message:"删除失败",
                                });
                            });
                    })
                    .catch(error=>{
                        this.$notify({
                            type:"info",
                            message:"已取消删除"
                        })
                    });

            },
            //页面改变方法
            pageChange(val){
                // alert(val); //点击对应的页数输出对应页码
                //设置当前页码
                this.p=val;
                //重新获取数据
                this.getGoodsData();
            },
            //页面搜索
            searchChange(){
                this.page=1;  //初始化搜索页面
                this.getGoodsData(); //重新获取数据
            },
        },
        //挂载完毕
        mounted(){
            //调用获取分类数据
            this.getTypeData();
            //调用商品展示的数据
            this.getGoodsData();
        },
})
</script>
</html>