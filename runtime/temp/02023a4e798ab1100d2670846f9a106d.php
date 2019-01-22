<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:72:"F:\php\wuif1808\shop\public/../application/admin\view\message\index.html";i:1544077131;s:62:"F:\php\wuif1808\shop\application\admin\view\common\header.html";i:1543480102;}*/ ?>
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
        <el-breadcrumb-item>管理员管理</el-breadcrumb-item>
    </el-breadcrumb>
    <el-tabs v-model="activeName"   style="margin-top: 30px">
        <el-tab-pane label="查看管理员" name="show">
            <el-row>
                <el-col :span="6">
                    <el-button type="danger" @click="delAll">
                        批量删除
                    </el-button>
                </el-col>
                <el-col :span="12">
                    <el-input v-model="search" @change="searchData" placeholder="请输入搜索内容"></el-input>
                </el-col>
            </el-row>
            <el-table
              v-loading="loading"
              :data="tableData"
              style="width: 100%"
              @selection-change="change"
            >
              <el-table-column
                type="selection"
                width="50"
                align="center"
              >
              </el-table-column>
            <el-table-column
            prop="id"
            label="ID"
            width="100px"
            align="center"
            >
            </el-table-column>
                <el-table-column
                        prop="username"
                        label="用户名"
                        align="center"
                >
                </el-table-column>
                <el-table-column
                        prop="status"
                        label="状态"
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
                <el-table-column
                        prop="time"
                        label="注册时间"
                        align="center"
                >
                </el-table-column>
                <el-table-column
                        prop=""
                        label="操作"
                        align="center"
                >
                    <template slot-scope="scope">
                            <el-button type="success" @click="saveData1(scope.row)">修改</el-button>
                            <el-button type="danger" @click="del(scope.row)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div style="text-align: center;margin: 10px 0px">
                <template v-if="tableData.length">
                    <el-pagination
                            @current-change="currentPage"
                            background
                            :page-size="size"
                            layout="prev, pager, next"
                            :total="tot">
                    </el-pagination>
                </template>
            </div>
        </el-tab-pane>
        <el-tab-pane label="添加管理员" name="add">
            <el-form :model="formData" :rules="rules" ref="add" label-width="100px">
                <el-form-item label="账户名" prop="username">
                    <el-input v-model="formData.username" placeholder="请输入账户名"></el-input>
                </el-form-item>
                <el-form-item  label="密码" prop="password" >
                    <el-input v-model="formData.password" type="password" placeholder="请输入密码"></el-input>
                </el-form-item>
                <el-form-item  label="确认密码" prop="repassword">
                    <el-input v-model="formData.repassword" type="password" placeholder="请输入确认密码"></el-input>
                </el-form-item>
                <el-form-item label="用户状态" prop="status">
                    <template>
                        <el-radio v-model="formData.status" label="0">白名单</el-radio>
                        <el-radio v-model="formData.status" label="1">黑名单</el-radio>
                    </template>
                </el-form-item>
                <el-button type="success" @click="submitData">提交</el-button>
                <el-button type="danger" @click="resetData">重置</el-button>
            </el-form>
        </el-tab-pane>
    </el-tabs>
<!--修改页面-->
    <el-dialog
            title="修改账户"
            :visible.sync="saveBoxShow"
            width="100%"
    >
        <el-form :rules="saveRules" ref="edit" :model="saveData" >
            <el-form-item label="用户名"  label-width="90px">
                <el-input  disabled v-model="saveData.username"></el-input>
            </el-form-item>
            <el-form-item label="新密码" label-width="90px" prop="password" >
                <el-input  type="password" v-model="saveData.password" palceholder="请输入密码"></el-input>
            </el-form-item>
            <el-form-item label="确认密码" label-width="90px" prop="repassword">
                <el-input  type="password" v-model="saveData.repassword" palceholder="请输入确认密码"></el-input>
            </el-form-item>
        </el-form>
        <span slot="footer" class="dialog-footer">
    <el-button @click="saveBoxShow = false">取 消</el-button>
    <el-button type="primary" @click="submitSaveData">确 定</el-button>
  </span>
    </el-dialog>
</div>
</body>
<script>
    //密码检验规则
    var checkPassword = (rule, value, callback) => {
        if (value === '') {
            callback(new Error('请输入密码'));
    //判断密码长度
        }else if(value.length<6 || value.length>12){
            callback(new Error('密码长度在6-12位之间'));
        } else {
            // if (this.ruleForm2.checkPass !== '') {
            //     this.$refs.ruleForm2.validateField('checkPass');
            // }
            callback();
        }
    };
    var checkRepassword = (rule, value, callback) => {
        if (value === '') {
            callback(new Error('请输入确认密码'));
        }else if(vue.formData.password!=value){
            callback(new Error('两次密码不一致'));
        } else {
            // if (this.ruleForm2.checkPass !== '') {
            //     this.$refs.ruleForm2.validateField('checkPass');
            // }
            callback();
        }
    };
    let checkUsername =(rule, value, callback)=>{
        //检验用户名是否被注册
        axios.get(`/admin/message/checkusername?username=${value}`)
            .then(response=>{
                //判断是否注册
                if(response.data==1){
                    callback(new Error('该账户已被注册'));//验证消息
                }else{
                    callback();
                }
            })
            .catch();
    };
    var checkRepassword2 = (rule, value, callback) => {
        if (value === '') {
            callback(new Error('请输入确认密码'));
        }else if(vue.saveData.password!=value){
            callback(new Error('两次密码不一致'));
        } else {
            // if (this.ruleForm2.checkPass !== '') {
            //     this.$refs.ruleForm2.validateField('checkPass');
            // }
            callback();
        }
    };
    let vue=new Vue({
        el:"#app",
        data: {
            search:"",
            tableData:[],
            tot:0,
            size:0,
            p:1,
            dialogVisible:false, //是否显示修改页面
            pass:"",
            repass:"",
            activeName: "show",
            del_ids:[],
            formData: {
                username: "",
                password: "",
                repassword: "",
                status: "0"
            },
            updateData:{
              username:"",
              password:""
            },
            rules: {
                //账户的规则
                username: [
                    //用户名唯一
                    { required: true, message: '请输入用户名', trigger: 'blur' },
                    //用户长度超出限制
                    { min: 3, max: 12, message: '长度在 6 到 12 个字符', trigger: 'blur' },
                    //自定义验证规则
                    {validator:checkUsername, trigger:'blur'}
                ],
                //密码规则
                password:[
                    //判断是否存在
                    { required: true, message: '请输入密码', trigger: 'blur' },
                    //书写自定义规则
                    { validator: checkPassword, trigger: 'blur' }

                ],
                repassword: [
                    { required: true, message: '请输入确认密码', trigger: 'blur' },
                    { validator: checkRepassword, trigger: 'blur' }
                ],
            },
            loading:false,
            saveData:{},
            saveBoxShow:false,
            saveRules: {
                //密码规则
                password:[
                    //判断是否存在
                    { required: true, message: '请输入密码', trigger: 'blur' },
                    //书写自定义规则
                    { validator: checkPassword, trigger: 'blur' }

                ],
                repassword: [
                    { required: true, message: '请输入确认密码', trigger: 'blur' },
                    { validator: checkRepassword2, trigger: 'blur' }
                ],
            },
        },
        //事件处理
        methods:{
            //重置事件
            resetData(){
                this.formData={
                    username: "",
                    password: "",
                    repassword: "",
                    status: "0"
                };
                //重置表单的提示框
                this.$refs['add'].resetFields();
            },
            //提交的事件
            submitData(){
                //验证添加的表单
                this.$refs['add'].validate((valid) => {
                    //valid 值是true false
                    if (valid) {
                        //发送axios请求
                        axios.post("<?php echo url('message/insertdata'); ?>",this.formData)
                            .then(response=>{
                                if(response.data.code==200){
                                    this.$message({
                                        type:"success",
                                        message:response.data.info
                                    });
                                    this.resetData(); //重置数据
                                    this.getAdminData(); //更新查看管理员的数据
                                    this.activeName="show";
                                }else{
                                    this.$message({
                                        type:"error",
                                        message:response.data.info
                                    });
                                }
                            })
                            .catch();
                    } else {
                        //如果错误提示错误信息
                        return false;
                    }
                });
            },
            //获取数据方法
            getAdminData(){
                //无刷新获取数据
                axios.get(`/admin/message/getadmindata?search=${this.search}&p=${this.p}`)
                    .then(response=>{
                        this.tableData=response.data.data;
                        this.tot=response.data.tot;  //总页数
                        this.size=response.data.size; //总页数下的分栏
                        //提示信息
                        this.$message({
                            type:"success",
                            message:"获取数据成功"
                        });
                        // this.getAdminData();
                    })
                    .catch(error=>{
                        this.$message({
                            type:"error",
                            message:"获取数据失败"
                        });
                    });
            },
            //对应页的处理
            currentPage(val){
                // alert(val);  //显示对应页的页码
                //设置默认页码
                this.p=val;  //是p等于对应的页码
                this.getAdminData();
            },
            //搜索内容
            searchData(){
                // this.p=1;  //如果换页搜索不到 就加上这句
              this.getAdminData();
            },
            //修改用户状态
            changeStatus(id,status){
                // alert(id);
                // alert(status); //对应的id跟状态
                status=status==1?0:1;
                //发送请求修改数据
                axios.post('<?php echo url("message/savestatus"); ?>',{id:id,status:status})
                    .then(response=>{
                        //判断是否修改成功
                        if(response.data==1){
                            this.$message({
                                type:"success",
                                message:"修改用户状态成功"
                            });
                            this.getAdminData();
                        }else{
                            this.$message({
                                type:"error",
                                message:"修改用户状态失败"
                            });

                        }
                    })
                    .catch();
            },
            //删除用户
            del:function (r) {
                // console.log(r);
                this.$confirm("此操作将永久删除该文件，是否继续？","提示",{
                  confirmButtonText:'确定',
                  cancelButtonText:'取消',
                    type:'warning'//messagebox 弹框
                }).then(()=>{
                    axios.delete("/admin/message/del?id="+r.id).then(response=>{
                        //这种的用get.id ,并且修改按钮的绑定需要加上id，这样r就是id， 把+id变成+r即可
                        if(response.data==1){
                            this.$message({
                                type:"success",
                                message:"删除成功"
                            });
                            this.getAdminData();
                        }
                    }).catch();
                    // axios.delete("/admin/message/del",{data:{id:r.id}}).then(response=>{
                    //     this.$message({
                    //         type:"success",
                    //         message:"删除成功"
                    //     });
                    //     this.getAdminData();
                    // }).catch(error=>{
                    //     this.$message({
                    //         type:"error",
                    //         message:"删除失败"
                    //     })
                    // });
                }).catch(()=>{
                   this.$message({
                       type:'info',
                       message:"已取消删除"
                   })
                });
            },
            //获取批量删除用户的id
            change:function (val) {
                // // console.log(val); //对应的id数组
                // this.del_ids=val.map(v=>v.id);
                // // console.log(this.del_ids);  //对应id的数组 类似数组

                //另一种思路
                this.del_ids=[];  //如果不写 就会一直重复
                val.forEach(item=>{
                    this.del_ids.push(item.id);
                });
                console.log(this.del_ids);  //同样是一个类似于数组的东西，里面有id
            },
            //批量删除操作
            delAll:function () {
                //判断用户是否勾选id
                if(this.del_ids.length===0){
                    //请选择数据
                    this.$message({
                       type:"error",
                       message:"请选择删除的数据"
                    });
                    return;
                }
                this.$confirm('此操作将永久删除数据，是否继续','提示',{
                    confirmButtonText:"确定",
                    cancelButtonText:"取消",
                    type:'warning'
                }).then(()=>{
                    // 另一种方法
                    let ids=this.del_ids.join(","); //用逗号拼接成一个数组
                    console.log(ids);   //点击确认后会出现对应的id
                    axios.delete("/admin/message/del?id="+ids).then(response=>{
                        if(response.data==1){
                            this.$message({
                                type:"success",
                                message:"删除成功"
                            });
                            this.del_ids=[];
                            this.getAdminData();
                        }
                    }).catch();  //这也是用get方式获取id后删除
                 // axios.delete("/admin/message/del",{data:{ids:JSON.stringify(this.del_ids)}}).then(response=>{
                 //     this.$message({
                 //         type:"success",
                 //         message:"删除成功"
                 //     });
                 //     this.getAdminData();
                 // }).catch(error=>{
                 //     this.$message({
                 //         type:"error",
                 //         message:"删除失败"
                 //     })
                 // });
                }).catch(()=>{
                    this.$message({
                        type:'info',
                        message:"已取消删除"
                    })
                })
            },
            //点击修改按钮
            saveData1(val){
                this.saveBoxShow=true;
                this.saveData={
                    id:val.id,
                    username:val.username,
                    password:"",
                    repassword:""
                }
            },
            //提交修改操作
            submitSaveData(){
                //检验表单
                this.$refs['edit'].validate((valid)=>{
                    //valid 值是true false
                    if(valid){
                        //发送axios请求
                        //提交表单数据
                        axios.post("/admin/message/savedata",this.saveData).then(response=>{
                            if(response.data==1){
                                this.$message({
                                    type:"success",
                                    message:"修改成功"
                                });
                                this.getAdminData();
                                this.saveBoxShow=false;
                                // this.$refs['edit'].resetFields();
                            }else{
                                this.$message({
                                    type:"error",
                                    message:"修改失败"
                                })
                            }
                        }).catch();
                    }else{
                        //如果错误提示错误信息
                        return false;
                    }
                })
            },
        },
        //挂载完毕
        mounted(){
            this.getAdminData();
        },
        watch:{
            saveBoxShow(val){
                // console.log(val);  //点击修改是true 点击确定是false
                if(!val){
                    this.$refs['edit'].resetFields();
                }
            }
        }
})
</script>
</html>