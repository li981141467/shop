<?php
/**
 * Created by PhpStorm.
 * User: 98114
 * Date: 2018/12/6
 * Time: 15:19
 */

namespace app\admin\controller;
use app\admin\model\Comment as CommentModel;
class Comment extends Lock
{
    function index(){
        return view();
    }

    //接收获取的数据的方法
    public function getdata(){
        //获取评论数据

        $page=input("get.page");
        $search=input("get.search");

        //设置每页的个数
        $size=1;

        //实例化数据模型
        $commentModel = new CommentModel();

        //计算数据个数
        $tot=$commentModel
            //查询的字段
            ->field("comment.*,goods.short_name,goods.name,user,username,goods.cover_img")
            //关联用户表
            ->join("user",'comment.user_id=user.id')
            //关联商品表
            ->join("goods","comment.goods_id=goods.id")
            //在哪查
            ->where("user.username like '%$search%'")
            ->count();
//        echo $tot;
//        exit();
        //查询数据

        $commentData = $commentModel
            //查询的字段
        ->field("comment.*,goods.short_name,goods.name,user.username,goods.cover_img")
            //关联用户表
        ->join("user",'comment.user_id=user.id')
        //关联商品表
        ->join("goods","comment.goods_id=goods.id")
        //倒序
        ->order("comment.id desc")
        ->where("user.username like '%$search%'")
            //分页方法
        ->page($page,$size)
        //查询所有
        ->select();

//        echo json_encode($commentData);

        $arr=[
            "tot"=>$tot,
            "size"=>$size,
            "data"=>$commentData
        ];

        echo json_encode($arr);
    }

    //改变状态
    public function savestatus(){
        $data=input("post.");

        if(CommentModel::update($data)){
            echo 1;
        }else{
            echo 0;
        }

    }
}