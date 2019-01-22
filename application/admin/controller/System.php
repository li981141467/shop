<?php
/**
 * Created by PhpStorm.
 * User: 98114
 * Date: 2018/12/3
 * Time: 11:26
 */
//命名空间
namespace app\admin\controller;
//导入系统控制器
use think\Controller;
use app\admin\model\Admin as AdminModel;
class System extends Lock
{
    //系统管理首页方法
    //1.搞定左侧菜单
    //2.创建对应控制器
    //3.搞定系统管理页面部分
    //4.从webconfig.json 获取数据展示在页面上
    //5.logo图片的修改功能
    //6.完成配置的修改操作
    //7.减少图片冗余
        //1.将所有上传的图片存放到临时目录
        //2.将需要的图片拷贝到指定目录

    public function index(){
//        copy("./uploads/logo.png","./uploads/config/logo.png");
        return view();
}
    public function system(){
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
    private function get(){
        //获取文件中的内容
        $file=file_get_contents("./static/webConfig.json");
        echo $file;
    }
    private function post(){
        //接受表单提交的所有数据
        $data=input("post.");
        //判断是否修改图片
        if($data['logo']!=$data['oldLogo']){
            //从临时目录拷贝到指定目录
            $tmpFile=$data['logo'];
//            echo $tmpFile;
            //获取拷贝的位置
            $newFile = str_replace('/tmp/','/config/',$tmpFile);
            //需要创建目录
            if(!file_exists(".".dirname($newFile))){
                mkdir('.'.dirname($newFile));
            }
            //进行文件拷贝
            copy('.'.$tmpFile,'.'.$newFile);
            $data['logo']=$newFile;

            if(file_exists('.'.$data['oldLogo'])){
                unlink('.'.$data['oldLogo']);
            }
            //删除原图
        }
        //删除network里面的链接
        unset($data['oldLogo']);
        //转换json
         $json=json_encode($data);
//         echo $json;
        if(file_put_contents('./static/webConfig.json',$json)){
            $message=[
                "code"=>200,
                "info"=>"更新成功"
            ];
        }else{
            $message=[
                "code"=>400,
                "info"=>"更新失败"
            ];
        };
        echo json_encode($message);
    }
    //修改密码的界面
    public function editpass(){
        return view();
    }
    //处理修改密码相关的处理
    public function pass()
    {
        // 判断请求类型
        $request = request();
        $type = strtolower($request->method());
        switch ($type) {
            case 'get':
                return $this->getpass();
                break;
            case 'post':
            return $this->savepass();
            break;
        }
    }
    //获取用户修改的数据
    private function getpass(){
        //获取用户id
        $id = session("uekshop_message_id");

        //查询对应数据
        $data = AdminModel::get($id);
        echo json_encode($data);  //输出对应的id用户信息 是一个对象  访问 system/pass
    }
    //更新数据方法
    private function savepass(){
        //接收post提交的数据
        $post=input("post.");
        //如果存在密码进行加密
        if(isset($post['password'])){
            $post['password']=md5($post['password']);
        }
        //更新数据库数据
        $adminModel=new AdminModel();

        $adminData = $adminModel->find($post['id']);
//        echo $adminData;
//        if ($adminData['password']== $post['password'] && $adminData['status'] == $post['status']){
//            echo 0;
//        }else{
            if($adminModel->update($post)){
                echo 1;
            }else {
                echo 0;
            }
//        }
    }
}