<?php
//声明命名空间
namespace app\api\controller;
//导入系统控制器
use think\Controller;
//导入数据模型
use app\admin\model\Banner as BannerModel;
//导入分类模型
use app\admin\model\Type as TypeModel;
//系统方法
use think\Db;

//声明控制器
class Base extends Controller{
    //获取网站的配置信息
    function getwebconfig(){
//        echo 1;
        //读取文件
       $fileData= file_get_contents("./static/webConfig.json");
//       var_dump($fileData);
        echo $fileData;
    }

    //获取网站轮播图
    function getsliderdata(){
        //实例化数据
        $bannerModel= new BannerModel();
        //获取数据
        $data =$bannerModel->order("sort desc")->select();

//        var_dump($data);  // 轮播图数据 在api/base/getsliderdata
        echo json_encode($data);
    }
    //获取菜单分类接口
//    $arr=[
//        [
//            "name":"一级分类"
//            "children":[
//
//                ]
//            ]
//
//        ]
    function gettypedata(){
        //查询出所有分类数据
//        $data=TypeModel::all();
////        dump($data);
//        dump($this->treedata($data,0));
        //系统方法
        $data=Db::table("type")->select();
//        var_dump($data);
//        dump($this->treedata($data,0));
        //分类的样子
//        echo "<pre>";
//        print_r($this->treedata($data,0));

        $typeData=$this->treedata($data);
        echo json_encode($typeData);
    }
    //将数据转换成树形结构
    function treedata($data,$pid=0){
        //$data 所有的分类数据
        //$pid 获取子类
        //新数组
        $newArr=[];
        //遍历所有数据
        foreach ($data as $key =>$value){
            //判断是不是顶级分类
            if($value['parent_id']==$pid){
                $newArr[$value['id']]=$value;
                $newArr[$value['id']]['children']=$this->treedata($data,$value['id']);

                //$value['id']即为下一级分类的$pid;
            }
        }
        return $newArr;
    }

    //获取明星单品
    function getgoodsdata(){
        //从商品表中获取数据
        //Db 数据库 ，table goods
        $data=Db::table("goods")->field("id,name,info,now_price,cover_img")->order("id desc")->limit(6)->select();
//        var_dump($data);
        echo json_encode($data);
    }

    //获取楼层数据
    function getfloordata(){
        //获取楼层数据
        $data=Db::table("type")->where("parent_id=0")->select();
//        var_dump($data); //顶级分类数据  //带索引 二维数组
        //遍历
        //连接符很重要
        foreach ($data as $key => &$value){
//            var_dump($value);  //顶级分了数据  不带索引 一维数组
            //查找楼层对应的三级分类
            $value["type"]=Db::table("type")->where("level = 3 and path like '%,$value[id],%'")->select();

            //处理数据
            $typeIdArr=[];
            foreach ($value['type'] as $k =>$v){
                $typeIdArr[]=$v["id"];
            }
            $type_id=join($typeIdArr,",");  // 将数据转换成数据
//            var_dump($type_id);  //string '7,10,12,13  //转成数组

            //查找楼层对应的商品
            $value["goods"]=Db::table("goods")->where("type_id in('$type_id')")->limit(8)->select();
//            echo "<pre>";
//            print_r($value);  //这样可以查到一二三级分类

//            select * from goods where id in (20,30,50) ;  获取id是20或者是30或者是50的
        }
        echo json_encode($data);
    }
}
?>