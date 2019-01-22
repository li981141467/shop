<?php
//声明命名空间
namespace app\api\controller;
//导入系统控制器
use think\Controller;
//系统方法
use think\Db;

//声明控制器
class Address extends Controller{
    //获取地址方法
    public function index(){
        //获取用户的id
        $id=input("id");
//        echo $id;  //http://www.shop.com/api/address/index?id=23

        //查询收货地址
        $data=Db::table("address")->where("uid=$id")->order("is_default desc")->select();

//        var_dump($data);
        echo json_encode($data);
    }

    //查询城市数据
    public function city(){
        //获取省份
        $province=Db::table("sys_province")->field("province_id id,province_name name")->select();
        $city=Db::table("sys_city")->field("city_id id,city_name name,province_id")->select();
        $area=Db::table("sys_district")->field("district_id id,district_name name,city_id")->select();

//        var_dump($provice);
//        var_dump($city);
//        var_dump($area);

        //将数据格式化
        foreach ($province as $key =>&$value) {
            //空数组
            $value['children']=[];
            //给省追加城市
            foreach ($city as $k => $v){
                if($v['province_id']==$value['id']){
                    $value['children'][]=$v;
                }
            }
        }

        foreach ($province as $key =>&$value){
            foreach ($value['children'] as $k =>&$v){
                $v['children']=[];
                foreach ($area as $k1 => $v1){
                    if($v['id']==$v1['city_id']){
                        $v['children'][]=$v1;
                    }
                }
            }
        }
        echo json_encode($province);
    }

    //接收提交过来的地址信息
    public function insert(){

        //接收信息
        $data=input("post.");
//        var_dump($data) ;  //在network里看   这里不能echo，否则会让转换成数组

        //判断是否设置默认地址
        if($data['is_default']==1){

            Db::table("address")->where("uid=$data[uid]")->update(["is_default"=>0]);

        }
        //存储数据
        $res=Db::table("address")->insert($data);

        if($res){
            echo 1;
        }else{
            echo 0;
        }

    }


    //改变默认,可以有，但没必要
    public function changeaddr(){

        $id=input('id');
//        echo $id; //对应的r
        if($id){
            Db::table("address")->where("id=$id")->update(["is_default"=>1]);
            Db::table("address")->where("id!=$id")->update(["is_default"=>0]);
        }

    }
}
?>