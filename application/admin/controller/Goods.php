<?php
/**
 * Created by PhpStorm.
 * User: 98114
 * Date: 2018/12/10
 * Time: 14:52
 */
//声明命名空间
namespace app\admin\controller;
//主要作用区分
use app\admin\model\Goods as GoodsModel;

class Goods extends Lock
{
    public function index(){
        return view();
    }

    //接收提交的数据
    public function insert(){
        //接收表单时提交的所有数据
        $post=input("post.");
        //增加创建时间
        $post['create_time']=time();

        //将封面图片移动到指定目录
        $temFile=$post['cover_img'];
        $newFile=str_replace("tmp",'goods',$post['cover_img']);
        $dirName=dirname($newFile);
//        echo $dirName;  /uploads/goods/20181211  对应的当天的文件夹名
        if(!file_exists(".".$dirName)){
            mkdir(".".$dirName);
        }
        //进行图片移动
        copy(".".$temFile,".".$newFile);
        $post["cover_img"]=$newFile; //把tmp改成goods

        //查看更多图片
        foreach ($post['imgs'] as $key => $value){
            //临时文件
            $temFile=$value;
            //获取新的文件地址
            $newFile=str_replace("tmp",'goods',$value);
            $dirName=dirname($newFile);
            if(!file_exists(".".$dirName)){
                mkdir(".".$dirName);
            }

            //进行更多图片移动
            copy(".".$temFile,".".$newFile);
            $post['imgs'][ $key]=$newFile;  //把tmp改成goods
//            var_dump($newFile);
        }
//        var_dump($newFile);

        //因为数据库中不能进行数组的存储，我们可以把它转换成字符串或者JSON格式
//        $post['imgs']=join(",",$post['imgs']);   //转化成字符串
        $post['imgs']=json_encode($post['imgs']); //转换成json


//            $post["save_time"]=0;
//            $post["browse_num"]=0;
//            $post["collect_num"]=0;
        //如果不写会报未知错误

//        var_dump($post['imgs']);
//            var_dump($post);
        //实例化数据模型
        $goodsModel=new GoodsModel();

//        存入数据库
        if($goodsModel->save($post)){
            echo 1;
        }else{
            echo 0;
        }
    }
    //获取商品数据
    //接口请求的数据 search/p
    //返回的数据/总页数/每页显示的页数
    public function getgoodsdata(){

        $p=input("get.p");
        $search=input("get.search");

        //设置每页的个数
        $size=2;

        //实例化数据模型

        $goodsModel=new GoodsModel();

        //计算数据个数

        $tot=$goodsModel
            ->where("name like '%$search%'")
            ->whereOr("short_name like '%$search%'")
            ->count();

//        echo $tot;  //返回对应数据库里的总条数
        //获取每页展示的数据

        $tableData=$goodsModel
            //查询的字段
            ->field("goods.*,type.name tname")
            //查询的条件
            ->where("goods.name like '%$search%'")
            //查询或者条件
            ->whereOr("goods.short_name like '%$search%'")
            //多表查询和分类表挂钩
            ->join("type","goods.type_id = type.id")
            //排序方法
            ->order("id desc")
            //分页方法
            ->page($p,$size)
            //查询方法
            ->select();

//        var_dump($tableData);
        $arr=[
          "tot"=>$tot,
          "size"=>$size,
          "data"=>$tableData
        ];

        echo json_encode($arr);
    }
    //更新商品上下架方法
    public function saveshelf(){
        //接受post请求的数据
        $post=input("post.");

//        var_dump($post);
        //修改数据

        $goodsModel = new GoodsModel();
        if($goodsModel->save(["is_shelf"=>$post['is_shelf']],['id'=>$post['id']])){
            echo 1;
        }else{
            echo 0;
        }
    }

    //删除数据
    public function deldata(){
        //接收数据
        $id=input("get.id");

        //实例化模型
        $goodsModel=new GoodsModel();

        //查询图片数据

        $goodsData=$goodsModel->find($id);
//        echo $goodsData;  //对应删除数据的内容

//        $arr=json_decode($goodsData['imgs']);
        //删除数据
        if($goodsModel->where("id = $id ")->delete()){
            //删除封面图片
            unlink(".".$goodsData['cover_img']);
            //删除多图
            $arr=json_decode($goodsData['imgs']);

            foreach ($arr as $key =>$value){
                unlink(".".$value);
            }
            echo 1;
        }else{
            echo 0;
        }
    }

    //修改方法的视图,跳页面
    public function edit(){
        return view();
    }
    //修改数据的方法（获取修改的数据）
    public function editdata(){
        //获取用户请求的id
        $id=input("get.id");

        //实例化数据模型
        $goodsModel=new GoodsModel();
        $goodsData=$goodsModel
            ->field("goods.*,type.path")
            ->join("type","goods.type_id=type.id")
            ->where("goods.id=$id")
            ->find();
        //这样可以找到path  "0,1,2,"

//        var_dump(json_encode($goodsData));   //对应的json

        echo json_encode($goodsData);

    }

    //更新数据方法
    public function update(){
        $post=input("post.");
//        var_dump($post);
        //增加更新时间
        $post['save_time']=time();

        //判断是否修改封面图片
        if($post['cover_img']!=$post['old_cover_img']){
            //将文件上传到指定目录
            $tmpfile=$post['cover_img'];
            $newfile=str_replace("tmp","goods",$tmpfile);

            //判断目录是否存在
            $dirname=dirname($newfile);
            if(!file_exists(".".$dirname)){
                mkdir(".".$dirname);
            }
            //移动文件
            copy('.'.$tmpfile,'.'.$newfile);
            $post['cover_img']=$newfile;
        }
        unset($post['old_cover_img']);  //不写就删除不掉
//        var_dump($post);
//        exit();

        //进行多图的处理
        $imgs=$post['imgs'];

        foreach ($imgs as $key =>$value){
            //判断是否在goods目录中，有老图
            if(strstr($value,"goods")){

            }else{
                //获取临时目录和新目录
                $tmpfile=$value;
                $newfile=str_replace('tmp','goods',$value);

                //判断目录是否存在
                $dirname=dirname($newfile);
                if(!file_exists(".".$dirname)){
                    mkdir(".".$dirname);
                }
                //移动文件
                copy('.'.$tmpfile,'.'.$newfile);
                $imgs[$key]=$newfile;  //不加这句新上传的图片的文件名不会由tmp变成goods
            }
        }
//        var_dump($imgs);  //图片都以变成goods
//        exit();

//        var_dump(input("post.deleteImg"));
        $delImg=$post['deleteImg'];
//        var_dump($delImg);   //输出对应的删除图片
        unset($post['deleteImg']);
//        var_dump($delImg);
        $post['imgs']=json_encode($post['imgs']);
//        $post['imgs']=join(",",$post['imgs']);  //如果使用,分隔

//        exit();
        //完成数据更新
        if(GoodsModel::update($post)){
            //如果修改了封面册
            if(input("post.cover_img")!=input("post.old_cover_img")){
                $file=input("post.old_cover_img");
                unlink(".".$file);
            }

            //删除对应多图
            if($delImg){

                foreach ($delImg as $key=>$value){
                    if(file_exists(".".$value)){
                        unlink(".".$value);
                    }
                }
            }
            echo 1;
        }else{
            echo 0;
        }
    }
}