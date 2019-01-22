<?php
//声明命名空间
namespace app\api\controller;
//导入系统控制器
use think\Controller;
//系统方法
use think\Db;
class Login extends  Controller{
    //处理登录方法
    public function check(){
        //接收用户名和密码
        $username=input("username");
        $password=input("password");

        //判断用户是否可以登录
        if($username){
            //判断密码
            if($password){
                //判断数据库中是否存在该用户
                $data=Db::table("user")->where(
                    [
                        "username"=>$username,
                        "password"=>md5($password),
                        "status"=>0
                    ])->find();


//                echo Db::getLastSql();  //获取最后一个sql语句  post不能在地址栏查看

//                var_dump($data);
                if($data){
                    $arr=[
                        "code"=>200,
                        "info"=>"登录成功",
                        "data"=>$data
                    ];
                }else{
                    $arr=[
                        "code"=>400,
                        "info"=>"登录失败"
                    ];
                }
            }else{
                $arr=[
                    "code"=>400,
                    "info"=>"请输入密码"
                ];
            }
        }else{
            $arr=[
              "code"=>400,
              "info"=>"请输入账户名"
            ];
        }
        echo json_encode($arr);
    }

    //检验手机号
    public function checkphone(){

        $phone=input("phone");
//        echo $phone;  //在newwork中会有 手机号

        //获取数据
        $data=Db::table("user")->where("telephone='$phone'")->find();

        if($data){
            echo 1;
        }else{
            echo 0;
        }
    }

    //发送短信接口
    public function fasong(){
        include "./Ucpaas.php";
        //接收手机号
        $phone=input("phone");

        //初始化必填
            //填写在开发者控制台首页上的Account Sid
        $options['accountsid']='1219a22290a8c359a937d13a63c9fc6f';
            //填写在开发者控制台首页上的Auth Token
        $options['token']='6a4c9ee15c4cb06e6984c57957a05f7a';

        //初始化 $options必填
        $ucpass = new \Ucpaas($options);

        //载入ucpass类

        $str="";
        //随机数
        for($i=0;$i<6;$i++){
            $str.=rand(0,9);
        }
        $appid = "da9775193ce2419fb440e4b441008d6a";	//应用的ID，可在开发者控制台内的短信产品下查看
        $templateid = "419428";    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
        $param = "$str,60"; //多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
        $mobile = $phone;
        $uid = "";

        $res= $ucpass->SendSms($appid,$templateid,$param,$mobile,$uid);
        $arr=json_decode($res,true);
//        var_dump($arr);
//        echo $arr;

        //判断
        if($arr['msg']=="OK"){
            $data=[
              "code"=>200,
              "str"=>$str
            ];
        }else{
            $data=[
                "code"=>400,
            ];
        }
        echo json_encode($data);

    }

    //提交注册
    public function zhuce(){
        $telephone=input("phone");
        $password=input("password");
        $repassword=input("repassword");
        $status=0;
        $time=time();

        //存入数据库
       $res= Db::table("user")->insert([
           "telephone"=>$telephone,
           "username"=>$telephone,
           "nickname"=>$telephone,
           "headimg"=>$telephone,
           "password"=>md5($password),
            "status"=>$status,
            "time"=>$time
        ]);

       if($res){
           echo 1;
       }else{
           echo 0;
       }
    }

}
?>