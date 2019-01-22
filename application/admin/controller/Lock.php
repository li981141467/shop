<?php
/**
 * Created by PhpStorm.
 * User: 98114
 * Date: 2018/12/4
 * Time: 11:26
 */

namespace app\admin\controller;
use think\Controller;

class Lock extends Controller
{
    //初始化方法
    public function _initialize(){
        //判断session中是否有用户
        if(!session("uekshop_message_id") ||!session("uekshop_message_username")){
            //跳转登录页面
            $this->error("请登录",url("Login/index"));
            exit;
        }
    }
}