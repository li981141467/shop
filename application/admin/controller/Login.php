<?php
/**
 * Created by PhpStorm.
 * User: 98114
 * Date: 2018/12/4
 * Time: 11:57
 */

namespace app\admin\controller;
//导入系统控制器
use think\Controller;
use app\admin\model\Admin as AdminModel;

class Login extends Controller
{
    function index(){
        return view();
    }
    //处理登录操作
    public function check(){
        //获取用户提交的账户和密码
        $username = input("post.username");
        $password = input("post.password");

        //判断用户是否书写账户
        if($username){
            //判断密码是否书写
            if($password){
                //获取该用户是否存在
                $admin = new AdminModel();

                $adminData=$admin->where([
                  "username"=>"$username",
                    "password"=>md5($password),
                    "status"=>0
                ])->find();
//                var_dump($adminData); //如果用户名不对就是null ，如果对就是对应的数据
                //判断是否获取数据
                if($adminData){
                    session("uekshop_message_id",$adminData['id']);
                    session("uekshop_message_username",$adminData['username']);
                    //登陆成功提示信息
                    $this->success("登录成功","/admin");
                }else{
                    $this->error("登录失败");
                }
            }else{
                $this->error("请输入密码");
            }
        }else{
            $this->error("请输入账户名");
        }
    }
    //退出功能
    public function logout(){
        session(null);
        $this->success("退出成功","Login/index");
    }
}