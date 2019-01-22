<?php
/**
 * Created by PhpStorm.
 * User: 98114
 * Date: 2018/11/29
 * Time: 13:58
 */
namespace app\admin\controller;
use think\Controller;

class Admin extends Lock
{
    function admin(){
        return view();
    }

}