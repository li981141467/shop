<?php
/**
 * Created by PhpStorm.
 * User: 98114
 * Date: 2018/11/30
 * Time: 16:48
 */

namespace app\admin\controller;


class Upload
{
    function upload(){
        //获取用户上传的地址
        $type=input("type","");
        //这段话在thinkphp完全开发手册里的杂项上传里
        //获取用户上传的图片
        $file=request()->file('file');
        //判断用户是否上传了文件
        if(empty($file)){
            $this->error('请选择上传文件');
        }
        //移动到框架应用根目录/public/uploads/ 目录下
        //将用户上传文件移动到指定目录
        //DS分隔符
        $info=$file->move(ROOT_PATH . 'public' . DS .'uploads' . DS . $type);
        //判断用户是否上传成功图片
        if($info){
            //返回图片地址
            return "/uploads/$type/".$info->getSaveName();
        }else{
            //上传失败获取错误信息
            return $file->getError();
        }
    }
}