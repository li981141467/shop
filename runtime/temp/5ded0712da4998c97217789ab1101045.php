<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"F:\php\wuif1808\shop\public/../application/admin\view\type\index.html";i:1544411827;s:62:"F:\php\wuif1808\shop\application\admin\view\common\header.html";i:1543480102;}*/ ?>
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
    .custom-tree-node {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
        padding-right: 8px;
    }
</style>
<body>
<div id="app">
    <el-breadcrumb separator="/">
        <el-breadcrumb-item>商品管理</el-breadcrumb-item>
        <el-breadcrumb-item>分类管理</el-breadcrumb-item>
    </el-breadcrumb>
    <el-tabs v-model="activeName"   style="margin-top: 30px">
        <el-tab-pane label="分类管理" name="show">
            <el-button type="success" size="mini" style="margin-bottom: 10px" @click="showBox=true">
                增加顶级分类
            </el-button>
            <el-tree
                    :data="typeData"
                    :expand-on-click-node="false"
                    :default-expand-all="true"
            >
                 <span class="custom-tree-node" slot-scope="{ node, data }">
                    <span>{{ data.name }}</span>
                    <span>
                        <template v-if="data.level<3">
                          <el-button
                                  type="text"
                                  size="mini"
                                  @click="addType(data)">
                            增加
                          </el-button>
                        </template>
                      <el-button
                              type="text"
                              size="mini"
                              @click="editType(data)">
                        编辑
                      </el-button>
                        <el-button
                                type="text"
                                size="mini"
                                @click="deletes(data)">
                        删除
                      </el-button>
                    </span>
                 </span>
            </el-tree>
        </el-tab-pane>
    </el-tabs>

    <el-dialog title="添加分类" :visible.sync="showBox">
        <el-form >
            <el-form-item label="所属分类" label-width="100px">
                <el-input  autocxomplete="off"  v-model="shuyu" readonly></el-input>
            </el-form-item>
            <el-form-item label="分类名称" label-width="100px">
                <el-input  autocxomplete="off" placeholder="请输入分类名称" v-model="topTypeName"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="showBox=false">取 消</el-button>
            <el-button type="primary" @click="addTypes">确 定</el-button>
        </div>
    </el-dialog>

    <!--//编辑-->

    <el-dialog title="添加分类" :visible.sync="showSaveBox">
        <el-form >
            <el-form-item label="分类名称" label-width="100px">
                <el-input  autocxomplete="off" placeholder="请输入分类名称" v-model="saveData.name"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <!--<el-button @click="showSaveBox=false">取 消</el-button>-->
            <el-button @click="cancels">取 消</el-button>
            <el-button type="primary" @click="saveDatas">确 定</el-button>
        </div>
    </el-dialog>
</div>
</body>
<script>
    let vue=new Vue({
        el:"#app",
        data: {
            //默认展示标签页
            activeName:"show",
            //分类数据
            typeData:[],
            //添加的显示隐藏
            showBox:false,
            //添加分类绑定的东西
            topTypeName:"",
            //属于什么分类,添加框上面的那个所属分类
            shuyu:"顶级分类", //这是点击增加，使对应的第一个输入框有对应的名字
            parent_id: 0,
            path:"0,",
            level: 1,
            //修改数据相关
            showSaveBox:false,
            //修改分类数据
            saveData:{},
        },
        //方法
        methods:{
            //获取分类数据
            getTypedata(){
                //获取数据
                axios.get("/admin/type/getdata").then(response=>{
                    //设置初始化数据
                    this.typeData=response.data;
                    //提示信息
                    this.$notify({
                        type:"success",
                        message:"获取数据成功"
                    })
                }).catch(error=>{
                    this.$notify({
                        type:"error",
                        message:"获取数据失败"
                    })
                })
            },
        //删除方法
            deletes(data){
                // console.log(data);  //对象 有对应的id
                //删除分类的方法
                this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.delete("/admin/type/deletedata?id="+data.id).then(response=>{
                        if(response.data==1){
                            this.$notify({
                                type:"success",
                                message:"删除成功"
                            });
                            this.getTypedata();
                        }else{
                            this.$notify({
                                type:"error",
                                message:"删除失败"
                            });
                        }
                        this.getTypedata();
                    }).catch(error=>{
                        this.$notify({
                            type:"error",
                            message:"未知错误"
                        });
                    });
                }).catch(() => {
                    this.$notify({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
         //添加分类
            addTypes(){
                //判断用户是否输入
                if(this.topTypeName){
                    //发送请求进行添加
                    // axios.post("/admin/type/addtype",{name:this.topTypeName,parent_id:'0',path:'0',level:1})  //这是一开始光一个增加顶级分类的按钮
                    axios.post("/admin/type/addtype",{name:this.topTypeName,parent_id:this.parent_id,path:this.path,level:this.level})
                        .then(response=>{
                            if(response.data==1){
                                this.$notify({
                                    type:"success",
                                    message:"添加成功"
                                });
                            }else{
                                this.$notify({
                                    type:"error",
                                    message:"添加失败"
                                })
                            }
                            this.getTypedata();
                            this.showBox=false;
                            this.topTypeName="";
                            //还原顶级分类
                            this.shuyu="顶级分类";
                            this.parent_id=0;
                            this.path="0,";
                            this.level=1;
                        })
                        .catch(error=>{
                            this.$notify({
                                type:"error",
                                message:"未知错误"
                            })
                        });
                }else{
                    this.$notify({
                        type:"error",
                        message:"请添加分类名称"
                    });
                }
            },
        //增加分类的方法
            addType(data){
                // console.log(data);//对应的数组，有对应的name

                //设置展示添加box
                this.showBox=true;
                //改变所属分类
                this.shuyu=data.name;  //这是点击增加，使对应的第一个输入框有对应的名字
                //设置父id
                this.parent_id=data.id;
                //设置path
                this.path=`${data.path}${data.id},`;
                //层级
                this.level=Number(data.level)+1;
                // console.log(this); //这个点击增加的this后面有在对应的getTypeData里面可以看到对应的父id，path,和level;
            },
        //修改分类的方法
            editType(data){
                //显示修改界面
              this.showSaveBox=true;
              //设置修改数据
              this.saveData=data;
            },
        //修改数据
            saveDatas(){
                //判断是否为空
                if(this.saveData.name){
                 //修改数据
                    axios.post("/admin/type/savedata",{id:this.saveData.id,name:this.saveData.name})
                        .then(response=>{
                            if(response.data==1){
                                this.$notify({
                                    type:"success",
                                    message:"修改成功"
                                });
                                this.showSaveBox=false;
                                this.getTypedata();
                            }else{
                                this.$notify({
                                    type:"error",
                                    message:"修改失败"
                                });
                            }
                        })
                        .catch(error=>{
                            this.$notify({
                                type:"error",
                                message:"未知错误"
                            });
                        });
                }else{
                    this.$message({
                        type:"error",
                        message:"请输入修改分类名称"
                    })
                }
            },
            //编辑取消不会修改页面
            cancels(){
                this.showSaveBox=false;
                //这样就不会因为双向绑定，点击取消后页面也修改了
                this.getTypedata();
            },
        },
        //挂载完毕
        mounted(){
            this.getTypedata();
        }
})
</script>
</html>