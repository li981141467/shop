<?php
/**
 * Created by PhpStorm.
 * User: 98114
 * Date: 2018/12/6
 * Time: 15:19
 */

namespace app\admin\controller;

use app\admin\model\User as UserModel;
class User extends Lock
{
    function index(){
        return view();
    }
    //获取数据的方法
    public function getuserdata(){
        //接收用户的请求
//        sleep(1);
        $search=input("get.search");
        $page=input("get.page");

        //每页显示个数
        $size=5;

        //实例化数据模型
        $userModel = new UserModel();

        //查询总数
        $tot =$userModel->where("username like '%$search%'")->count();
//        echo $tot;  //输出数据库里的总数

        $userdata =$userModel->where("username like '%$search%'")->order("id DESC")->page($page,$size)->select();
//        var_dump($userdata); //数据库内容的数组

        //将数据进行返回
        $arr=[
          "size"=>$size,
          "tot"=>$tot,
          "data"=>$userdata
        ];
        echo json_encode($arr);  //将数据库内容转换成jsON格式
    }

    //更新用户状态方法
    public function savestatus(){
        //接受post请求的数据
        $post=input("post.");

        //修改数据

        $adminModel = new userModel();
        if($adminModel->save(["status"=>$post['status']],['id'=>$post['id']])){
            echo 1;
        }else{
            echo 0;
        }
    }
}