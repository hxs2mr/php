<?php
namespace app\api\controller;
use think\Request;
use think\File;
use think\Controller;
class Image extends Controller
{
    public function upload()
    {
       $file =  Request::instance()->file('file');

       //给定一个目录upload

         $info = $file->move('upload');

         if($info&&$info->getPathname()){

            return show(1,'success','/'.$info->getPathname());
         }

         return show(0,'上传出错');
    }

}