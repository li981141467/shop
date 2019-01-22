<?php
/**
 * Created by PhpStorm.
 * User: 98114
 * Date: 2018/12/6
 * Time: 15:19
 */

namespace app\admin\controller;
use app\admin\model\Orders as OrdersModel;
use app\admin\model\OrdersItem as OrdersItemModel;
use function Couchbase\defaultDecoder;

class Orders extends Lock
{
    function index(){
        return view();
    }

    public function getorderdata(){

        $page=input("get.page");
        $search=input("get.search");

        //设置每页的个数
        $size=1;

        //实例化数据模型

        $orderModer=new OrdersModel();

        //计算数据个数
        $tot=$orderModer
            //查询的字段
            ->field("orders.*,user.username")
            //关联用户表
            ->join("user",'user.id=orders.uid')
            //在哪查
            ->where("user.username like '%$search%'")

            ->count();

//        echo $tot;
//        exit();
        //查询数据

        $orderData=$orderModer
            //查询的字段
            ->field("orders.*,user.username")
            //关联用户表
            ->join("user",'user.id=orders.uid')
            //倒序
            ->order("orders.id desc")
            ->where("user.username like '%$search%'")
            //分页
            ->page($page,$size)
            ->select();

//        echo json_encode($orderData);
        $arr=[
          "tot"=>$tot,
          "size"=>$size,
          "data"=>$orderData
        ];

        echo json_encode($arr);
    }


    //详情数据

    public function orderlist(){
        //接受id
        $id=input("get.id");

        //实例化模型

        $model=new OrdersItemModel();

        $orderItemData=$model->where("order_id=$id")->select();

        echo json_encode($orderItemData);

    }

    //改变订单状态
    public function savestatus(){
        $data=input("post.");

        //特殊状态
        switch ($data['status']){
            case '3';

            $data['fa_time']=time();  //发货时间的更新

            break;

            default;
        }
        //进行更新
        if(OrdersModel::update($data)){
            echo 1;
        }else{
            echo 0;
        }
    }
}