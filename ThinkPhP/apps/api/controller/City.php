<?php
namespace app\api\controller;
use think\Controller;
/**
 * Created by PhpStorm.
 * User: microtech
 * Date: 2018/2/4
 * Time: 16:01
 */
Class City extends Controller{
    protected $obj;
    public function _initialize()//抽离出公用部分  并初始化
    {
        $this->obj = model("City");
    }
    public function getFristCity()
    {
        $id = input('post.id');

        if(!$id)
        {
            $this->error('id不合法');
        }
        //通过id获取二级城市

        $citys = $this->obj->getNormalFirstCity($id);
        if(!$citys)
        {
            return show(0,'error');
        }

        return show(1,'success',$citys);
    }
}