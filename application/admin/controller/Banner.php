<?php
/**
 * Created by PhpStorm.
 * User: 98114
 * Date: 2018/11/29
 * Time: 16:17
 */

namespace app\admin\controller;
use think\controller;
use app\admin\model\Banner as BannerModel;

class Banner extends Lock
{
    function index(){
        return view();
    }
    function banner(){
        $request=request();
        $type=strtolower($request->method());
        switch ($type){
            case 'get':
                return $this->get();
                break;
            case 'put':
                return $this->put();
                break;
            case 'post':
                return $this->post();
                break;
            case 'delete':
                return $this->delete();
                break;
        }
    }
    protected function get(){
        $r=BannerModel::all();
        return json($r);
    }
    protected function put(){
        return BannerModel::update(input("put."));
    }
    protected function post(){
//        var_dump(input("post.")); //是一个数组
//        $banner=new BannerModel(input("post."));
//        $banner->save();
       return BannerModel::create(input("post."));  //ruturn了才能在netwrok里面看到
    }
    protected function delete(){
        $id=input("delete.id");
//        return $id;
        $ids=input("delete.ids");
//        return $ids;  //在network里可以看到选中的id,是个数组
//        var_dump(json_decode($ids));   // 这里会在network里输出一个数组
        if($id){
            return BannerModel::destroy($id);
        }
        if ($ids){
            return BannerModel::destroy(json_decode($ids));
        }
    }
}