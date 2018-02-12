<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
     return $this->fetch();
    }

    public function welcome()
    {
       // \phpmailer\Email::send("1363826037@qq.com","PHP学习","hello word");//邮件的发送  调用的地方
        return $this->fetch();
    }

    public function test()
    {
            \Map::getLat('贵州贵阳观山湖区');
            return '地图测试';
    }

    public function map()
    {
        $center =  \Map::getLat('贵州贵阳观山湖区');
        return  \Map::static_image('贵州贵阳观山湖区');
    }

    public function testemail()
    {
        \phpmailer\Email::send("1363826037@qq.com","PHP学习","hello word");
        return '测试邮件发送';
    }
}
