<?php
/**
 * Created by PhpStorm.
 * User: 98114
 * Date: 2018/11/29
 * Time: 9:24
 */
namespace app\test\controller;
use think\controller\Rest;  //需要use Rest

class Test  extends Rest
{
    function index(){
        return view();
    }
    public function user(){
        switch ($this->method){
            case 'get':return $this->get();
                break;
            case 'put':return $this->put();
                break;
            case 'post':return $this->post();
                break;
            case 'delete':return $this->delete();
        }
    }
//    protected function get(){
//        $name=input("get.name");
//        return $name;
//    }
    //以上第一种
    protected function get(){
        $id=input("get.id");
        if($id){
            return "某个用户的数据";
        }else{
            return "所有数据";
        }
    }
    protected function post(){
        $data=input("post.");
        return json($data);
    }
    protected function put(){
        $data=input("put.");
        return json($data);   //如果不转换成json 就不能使用
    }
    protected function delete(){
        $data=input("delete");
        return json($data);
    }
}