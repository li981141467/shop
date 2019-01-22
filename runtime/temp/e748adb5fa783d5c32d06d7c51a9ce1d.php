<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"F:\php\wuif1808\shop\public/../application/admin\view\banner\index.html";i:1543800612;s:62:"F:\php\wuif1808\shop\application\admin\view\common\header.html";i:1543480102;}*/ ?>
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
        <el-breadcrumb separator="/">
            <el-breadcrumb-item>系统管理</el-breadcrumb-item>
            <el-breadcrumb-item>轮播管理</el-breadcrumb-item>
        </el-breadcrumb>
        <el-tabs v-model="activeName"   style="margin-top: 30px">
            <el-tab-pane label="查看轮播" name="show">
                <el-table
                    :data="tableData"
                    style="width: 100%;margin-top: 30px"
                    border
                    @selection-change="change"
            >
                <el-table-column
                        type="selection"
                        width="50"
                        align="center"
                >
                </el-table-column>
                <!--type="selection"  是前面的对勾按钮-->
                <el-table-column
                        prop="id"
                        label="ID"
                        align="center"
                >
                </el-table-column>
                <el-table-column
                        prop="src"
                        label="图片"
                        align="center"
                >
                    <template slot-scope="scope">
                        <img :src="scope.row.src" alt="" style="width:100px;">
                    </template>
                </el-table-column>
                <el-table-column
                        prop="sort"
                        label="排序"
                        align="center"
                        sortable
                >
                    <!--sortable是给它排序-->
                </el-table-column>
                <el-table-column
                        label="操作"
                        align="center"
                >
                    <template slot-scope="scope">
                        <el-button type="warning" plain @click="update(scope.row)">修改</el-button>
                        <el-button  type="danger" plain @click="del(scope.row)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
                <el-button type="primary" style="margin-top: 30px" @click="delAll">批量删除</el-button>
            </el-tab-pane>
            <el-tab-pane label="添加轮播" name="add">
                <el-form
                        :model="formData"
                        label-width="80px"
                >
                        <el-form-item label="排序">
                            <el-input v-model="formData.sort"></el-input>
                        </el-form-item>
                        <el-form-item label="图片">
                            <el-upload
                                    class="avatar-uploader"
                                    action="<?php echo url('/admin/upload/upload'); ?>"
                                    :show-file-list="false"
                                    :on-success="success"
                            >
                                <img v-if="imageUrl" :src="imageUrl" class="avatar">
                                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                            </el-upload>
                        </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submit">添加</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
        </el-tabs>


        <el-dialog
                title="提示"
                :visible.sync="dialogVisible"
                width="100%"
                >
            <el-form :model="updateData" label-width="50px">
                <el-form-item label="排序">
                    <el-input v-model="updateData.sort"></el-input>
                </el-form-item>
                <el-form-item label="图片">
                    <el-upload
                            class="avatar-uploader"
                            action="<?php echo url('/admin/upload/upload'); ?>"
                            :show-file-list="false"
                            :on-success="success"
                    >
                        <img :src="updateData.src" class="avatar">
                    </el-upload>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
    <el-button @click="dialogVisible = false">取 消</el-button>
    <el-button type="primary" @click="submitUpdate">确 定</el-button>
  </span>
        </el-dialog>

    </div>
</body>
<script>
    new Vue({
        el:"#app",
        data:{
            tableData:[],   //数据
            url:"/admin/banner/banner",
            dialogImageUrl: '',
            dialogVisible: false,
            del_ids:[],
            activeName:"show",
            formData:{
                src:"",
                sort:"0"
            },
            imageUrl:"",
            updateData:{
                id:"",
                src:"",
                sort: ""
            },
            dialogVisible:false,
        },
        methods:{
            fetchData:function () {
               // axios.get("/admin/banner/banner").then(res=>{
               axios.get(this.url).then(res=>{
                   // console.log(res.data); //res.data就是所有的哪些对象一类的，里面有数据库的东西
                   // this.tableData=res.data;
                   if(res.status===200){
                       this.$message({
                           type:"success",
                           message:"获取成功"
                       });
                       this.tableData=res.data;
                   }else{
                       this.$message({
                           type:"error",
                           message:"获取失败"
                       });
                   }
               }).catch(()=>{
                   this.$message({
                       type:"error",
                       message:"获取失败"
                   });
               })
            },
            del:function(r){
                // console.log(r); 这个就是数据库所对应的每个id
                this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'//messagebox 弹框
                }).then(() => {
                    axios.delete(this.url,{data:{id:r.id}}).then(res=>{
                        // console.log(res);//里面有id data:1
                        if(res.data===1){
                            this.$message({
                                type: 'success',
                                message: '删除成功!'
                            });
                            this.fetchData();
                        }else{
                            this.$message({
                                type: 'error',
                                message: '删除失败!'
                            });
                        }
                    }).catch(() => {
                        this.$message({
                            type: 'info',
                            message: '已取消删除'
                        });
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            change:function (val) {
                // console.log(val);  //对应的内容的数组
                this.del_ids=val.map(v=>v.id);
                // console.log(this.del_ids); //对应的数据的id
            },
            delAll:function () {
                if(this.del_ids.length===0){
                    return;
                }
                this.$confirm('此操作将永久删除数据, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.delete(this.url,{data:{ids:JSON.stringify(this.del_ids)}}).then(res=>{
                        if(res.data===this.del_ids.length){
                            this.$message({
                                type: 'success',
                                message: '删除成功!'
                            });
                            this.fetchData();
                        }else{
                            this.$message({
                                type: 'error',
                                message: '删除失败!'
                            });
                        }
                    }).catch(()=>{
                        this.$message({
                            type: 'error',
                            message: '删除失败!'
                        });
                    });
                    // console.log(this.del_ids);  //这就是选中的id，数组形式
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            success:function (r) {
                // console.log(r);  //这是图片的路径
                this.imageUrl=r; //上传后显示图片
                this.updateData.src=r;   //更新图片
            },
            submit:function () {
             if(!this.imageUrl){
                 this.$message({
                     type:"error",
                     message:"请先上传图片"
                 });
                 return;
             }
             let obj=this.formData;
             // console.log(obj);//即这个对象
             obj.src=this.imageUrl;
             axios.post(this.url,obj).then(res=>{
                 // console.log(res);  //data:""  status:200  添加后data里面是src;
                 if(res.data){
                     this.$message({
                         type:"success",
                         message:"添加成功"
                     });
                     this.fetchData();
                     this.activeName="show";

                 }else{
                     this.$message({
                         type:"error",
                         message:"添加失败"
                     })
                 }
                }).catch(()=>{
                 this.$message({
                     type:"error",
                     message:"添加失败"
                 })
             })
            },
            update:function (obj) {
                this.updateData=obj;
                this.dialogVisible=true;
            },
            submitUpdate:function () {
                let obj=this.updateData;
                // console.log(obj); //还是那个对象
                axios.put(this.url,obj).then(res=>{
                    // console.log(res);
                    if(res.data){
                        this.$message({
                            type:"success",
                            message:"修改成功"
                        });
                        this.dialogVisible=false;
                        this.fetchData();
                    }else{
                        this.$message({
                            type:"error",
                            message:"修改失败"
                        });
                    }
                }).catch(()=>{
                    this.$message({
                        type:"error",
                        message:"修改失败"
                    });
                })
            }
        },
        mounted:function () {
            this.fetchData();
        }
    })
</script>
</html>