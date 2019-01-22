<?php
/**
 * Created by PhpStorm.
 * User: 98114
 * Date: 2018/12/7
 * Time: 14:39
 */

namespace app\admin\controller;
use think\Controller;
use app\admin\model\Type as TypeModel;

class Type extends Lock
{
    function index(){
        return view();
    }
    function getdata(){
       $data= $this->getclassdata();
       echo json_encode($data);
    }
    public function getclassdata($pid=0){
        $typeModel = new TypeModel();

        $one=$typeModel->where("parent_id=$pid")->select();
//        return $one; //这时返回的是衣服
        foreach ($one as $key => $value){
//            $one[$key]['children']=$this->getclassdata($value['id']);
//            var_dump($key);
//            var_dump(json_encode($value));
            $arr=$this->getclassdata($value['id']);
            if($arr){
                //这样就不会多加children
                $one[$key]['children']=$arr;
            }

        }
        return $one;
    }
//    getclassdata(0){#查询顶级分类
//        #查询二级分类
//    foreach(){
//        //衣服的子类
//    getclassdata(1){
//        //男装
//        getclassdata(){
//            //领带
//            //西服
//}
//}
//}
//}

    //删除方法
    public function deletedata(){
        //接受数据
        $id = input("get.id");

//        echo $id; //对应的id

        //数据删除前的实例化

        $typeModel=new TypeModel();

        //删除数据
        $a=$typeModel->where("id = $id")->whereor("path like '%,$id,%'")->delete();

//        "delete from type where id =1 or path like '%,$id,%'"  //这是上一句话的意思
        if($a){
            echo 1;
        }else{
            echo 0;
        }

    }

    //添加分类方法
    public function addtype(){
        //接受数据
        $data=input("post.");

        //插入数据

        $typeModel=new TypeModel();

        if($typeModel->save($data)){
            echo 1;
        }else{
            echo 0;
        }

    }

    //修改分类的方法
    public function savedata(){
        //接收用户请求数据
        $id=input("post.id");
        $name=input("post.name");

        //实例化模型
        $typeModel=new TypeModel();

        //修改数据
        $res=$typeModel->save(["name"=>$name],["id"=>$id]);

        //判断是否修改成功
        if($res){
            echo 1;
        }else{
            echo 0;
        }
    }
}