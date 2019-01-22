<?php
//声明命名空间
namespace app\api\controller;
//导入系统控制器
use think\Controller;
//系统方法
use think\Db;

//声明控制器
class Order extends Controller{
    //结算数据
    public function createorder(){
//        //接收用户数据
        $data=input("post.");
//        //接收用户id
        $uid=$data["uid"];
//        //接收地址id
        $address_id=$data["address_id"];
//        //接收下单商品的数据
        $payGoodsData=$data["payGoodsData"];
//        var_dump($data);

        //订单号
        $code="uek_".time().rand().$uid;

        //从地址表查询相关地址信息
        $address=Db::table("address")->find($address_id);

//        var_dump($address);

        $shou_name=$address["shou_name"];
        $shou_phone=$address["shou_phone"];
        $shou_address=$address["shou_province"].$address["shou_city"].$address["shou_area"].$address["addr"];

        //计算支付总额

        $money=0;

        foreach ($payGoodsData as $key => $value){
            $money+=$value['now_price']*$value['num'];
        }

        //状态
        $status=0;
        $create_time=time();

        $datas=[

            "uid"=>$uid,
            "code"=>$code,
            "shou_name"=>$shou_name,
            "shou_phone"=>$shou_phone,
            "shou_addr"=>$shou_address,
            "money"=>$money,
            "status"=>$status,
            "create_time"=>$create_time,
            "discount_money"=>0,
            "pay_money"=>$money,
            "pay_time"=>time(),
            "fa_time"=>0,
            "shou_time"=>0,
            "comment_time"=>0,
        ];

        $order_id=Db::table("orders")->insertGetId($datas);

        if($order_id){
            foreach ($payGoodsData as $key => $value){
                $arr=[
                    "order_id"=>$order_id,
                    "goods_id"=>$value['id'],
                    "goods_name"=>$value['name'],
                    "goods_price"=>$value['now_price'],
                    "goods_img"=>$value['cover_img'],
                    "num"=>$value['num']
                ];

                Db::table("orders_item")->insert($arr);
            }
//            echo  1;
            echo $order_id;
        }else{
            echo 0;
        }
    }
}
?>