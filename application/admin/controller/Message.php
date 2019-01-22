<?php
/**
 * Created by PhpStorm.
 * User: 98114
 * Date: 2018/12/5
 * Time: 9:31
 */
//命名空间
namespace app\admin\controller;
use app\admin\model\Admin as AdminModel;
//声明控制器
class Message extends Lock
{
    function index(){
        return view();
    }
    //检验用户名是否被注册
    public function checkusername(){
        $username=input("username");
//        var_dump($username);  //输出获得的username
        //查询该用户是否被注册
        $adminModel = new AdminModel();
        $adminData = $adminModel->where("username='$username'")->find();

        //判断是否注册
        if($adminData){
            echo 1;
        }else{
            echo 0;
        }
    }
    //数据插入方法
    public function insertdata(){
        //接受数据
//        $data=input("post."); //这是接受整个的post数据
//        var_dump($data);  //对应的数组信息
        $username = input("post.username");
        $password = input("post.password");
        $repassword = input("post.repassword");
        $status = input("post.status");

        //开发网站过程中
        //1.前端需要进行验证
        //2.后端需要验证

        //判断是否书写用户名
        if($username){
            //判断密码
            if($password){
                //判断两次密码
                if($password ==$repassword){
                    //进行入库操作
                    $data=[
                        "username"=>$username,
                        "password"=>md5($password),
                        "status"=>$status,
                        "time"=>date("Y-m-d H:i:s")
                    ];
//                    var_dump($data); //输出对应的带时间的数组
                    //插入数据库
                    $adminModel=new AdminModel($data);
                    if($adminModel->save()){
                        $message=[
                            "code"=>200,
                            "info"=>"添加成功"
                        ];
                    }else{
                        $message=[
                            "code"=>400,
                            "info"=>"添加失败"
                        ];
                    }
                }else{
                    $message=[
                        "code"=>400,
                        "info"=>"两次密码输出不一致"
                    ];
                }
            }else{
                $message=[
                  "code"=>400,
                  "info"=>"请输入密码"
                ];
            }
        }else{
            $message=[
                "code"=>400,
                "info"=>"请输入用户名"
            ];
        }
        echo json_encode($message);
    }
    //获取对应数据
    public function getadmindata(){
        //获取地址栏参数
        $search=input("get.search");
        //获取页码
        $p=input("get.p","1");

        //如何获取数据

        $adminModel=new AdminModel();

        $size=5;  //手动控制下面有几页

        //获取数据

        $adminData=$adminModel->where("username like '%$search%'")->page($p,$size)->select();
        //以上是模糊匹配

//        var_dump($adminData);

        //获取数据总数
        $count=$adminModel->where("username like '%$search%'")->count();

//        echo $count;  //page-size="5"
//        exit;

        //格式化数据
        $arr=[
          "tot"=>$count,
          "data"=>$adminData,
          "size"=>$size
        ];
//        echo json_encode($adminData);最开始是获取所有的
        echo json_encode($arr);

        //输出的$arrdata: [{id: 13, username: "uek2", password: "e10adc3949ba59abbe56e057f20f883e", status: 1,…},…]
        //tot: 5
    }
    //更新用户状态方法
    public function savestatus(){
        //接受post请求的数据
        $post=input("post.");

        //修改数据

        $adminModel = new AdminModel();
        if($adminModel->save(["status"=>$post['status']],['id'=>$post['id']])){
            echo 1;
        }else{
            echo 0;
        }
    }
    //删除数据
//    function del(){
//        $id=input("delete.id");
//        $ids=input("delete.ids");
//        if ($id){
//            return AdminModel::destroy($id);
//        }else if($ids){
//            return AdminModel::destroy(json_decode($ids));
//        }
//    }
    //另一种删除方法，可以把删除跟批量删除结合起来
    public function del(){
        $id=input("get.id");
        if(AdminModel::destroy($id)){
            echo 1;
        }else{
            echo 0;
        }
    }
    //修改账户密码操作
    public function savedata(){
        //获取数据
        $post=input("post.");
//        var_dump($data);  //对应的数组信息
        //进行数据更新
        $adminModel=new AdminModel();

        //修改数据
        if($adminModel->save(["password"=>md5($post['password'])],["id"=>$post['id']])){
            echo 1;
        }else{
            echo 0;  //如果重复会echo 0
        }
    }
}