<?php
//声明命名空间
namespace app\api\controller;
//导入系统控制器
use think\Controller;
//系统方法
use think\Db;

//声明控制器
class Goods extends Controller{
    //获取商品数据
    function index(){
        //接收参数
        $id=input("id");
//        echo $id;
//        //查询对应的商品数据
        $data=Db::table("goods")->where("id=$id")->find();
//        http://www.shop.com/api/goods/index?id=27
        echo json_encode($data);
    }
    //获取评论数据
    function comment(){
        //接收参数
        $id=input("id");
        //接收数据

        //单查商品
//        $data=Db::table("comment")->where("goods_id=$id")->select();
//        http://www.shop.com/api/goods/comment?id=27 //注意这是goods的id

        //多品
                $data=Db::table("comment")->field("comment.*,user.username")->join("user","user.id =comment.user_id")->where("comment.goods_id=$id")->select();
                //看是否有信息
//                var_dump($data);
//                exit;
        /////

        //处理时间
        foreach ($data as $key => &$value){
            $value['times']=date("Y-m-d H:i:s",$value['time']);
        }
        echo json_encode($data);
    }
    //获取热销商品
    function hotgoods(){
        //获取商品id
        $id=input("id");

//        $id=28;

        //查询对应的分类信息
        $goods=Db::table("goods")->field("type_id")->where("id=$id")->find();
//        var_dump($goods);
        //获取对应的热销商品

        $hotGoods=Db::table("goods")->where("type_id = $goods[type_id] and is_shelf=0")->select();
//        var_dump($hotGoods); //对应商品的信息
        echo json_encode($hotGoods);
    }
    //获取分类的信息
    function gettypeinfo(){
        //获取用户请求的id
        $id=input("id");

        //查询相关数据
        $data=Db::table("type")->find($id);
//        var_dump($data);
        echo json_encode($data);

    }

    //获取商品分类id
    function getgoodsdata(){
        //接收分类的id
        $id=input("id");
//        echo $id;  //对应的id

        //当前页
        $p=input("p","1");
        //每页显示的个数
        $size=1;

        //判断分类的层级
        $data=Db::table("type")->find($id);
//        var_dump($data);  //根据?id获取对应的数据

        //根据分类的等级进行不同处理
        switch ($data['level']){
            case '1':
                //先查出三级分类
                $typeData=Db::table("type")->where("path like '%,$id,%' and level=3")->select();
//                var_dump($typeData); //二级分类下的三级分类
                $typeArr=[];
                foreach ($typeData as $key => $value){
                    $typeArr[]=$value["id"];
                }
//            echo join($typeArr,",");  //10,13 二级分类下三级内容的id
                $str=join($typeArr,",");

                //分页
                $tot=Db::table("goods")->where("type_id in($str)")->count();

                $goodsData=Db::table("goods")->where("type_id in($str)")->page($p,$size)->select();
                break;
            case '2':
                //先查出三级分类
                $typeData=Db::table("type")->where("parent_id = $id")->page($p,$size)->select();
//                var_dump($typeData); //二级分类下的三级分类
            $typeArr=[];
            foreach ($typeData as $key => $value){
                $typeArr[]=$value["id"];
            }
//            echo join($typeArr,",");  //10,13 二级分类下三级内容的id
            $str=join($typeArr,",");

            //分页
            $tot=Db::table("goods")->where("type_id in($str)")->count();

            $goodsData=Db::table("goods")->where("type_id in($str)")->page($p,$size)->select();
                break;
            case '3':
                $tot=Db::table("goods")->where("type_id = $id ")->count();

                $goodsData=Db::table("goods")->where("type_id = $id")->select();
                break;
        }
//        var_dump($goodsData);
        $arr=[
            "tot"=>$tot,
            "data"=>$goodsData,
            "size"=>$size,
            "p"=>$p
        ];

        echo json_encode($arr);
    }
}
?>