<?php
namespace app\index\controller;

use think\Controller;

class Index extends Base
{

    public function index()
    {
        //获取首页大图相关的数据和广告位

        $data0 =model('Featured')->getFeaturedIndexId(0);
        $data1 =model('Featured')->getFeaturedIndexId(1);


        //商品分类数据 数据吗，美食的数据

        $meishi_data=model('Deal')->getNormalDealCityId(12,($this->city)->id);

      //获取子分类
       $meishi_cates=model('Category')->getNormalRecommendCategory(12,4);

        return $this->fetch('',[
            'data0'=>$data0,
            'data1'=>$data1,

            'meishi_data'=>$meishi_data,
            'meishi_cates'=>$meishi_cates,//美食模块的数据

            'controller'=>'ms',//覆盖css
        ]);
    }
}
