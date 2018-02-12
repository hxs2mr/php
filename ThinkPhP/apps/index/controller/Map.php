<?php
namespace app\index\controller;

use think\Controller;

class Map extends Controller
{
   public function getMapImage($data){

       return \Map::static_image($data);//获取地图的图片
   }
}
