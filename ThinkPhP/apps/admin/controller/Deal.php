<?php
namespace app\admin\controller;

use think\console\command\make\Model;
use think\Controller;

class Deal extends Controller
{
    protected $obj;
    public function _initialize()//抽离出公用部分  并初始化
    {
        $this->obj = model("Deal");
    }

    public function index()
    {

        $data = input('get.');
        $sdata=[];
        if(!empty($data['category_id']))//模糊查询的数组封装
        {
            $sdata['category_id'] = $data['category_id'];
        }
        if(!empty($data['city_id']))//模糊查询的数组封装
        {
            $sdata['city_id'] = $data['city_id'];
        }
        if(!empty($data['start_time']) && !empty($data['end_time']&&
                strtotime($data['end_time']) > strtotime($data['start_time']))){//模糊查询的数组封装
            $sdata['create_time'] = [
                ['gt',strtotime($data['start_time'])],
                ['lt',strtotime($data['end_time'])],
            ];
        }

        if(!empty($data['name'])){//模糊查询的数组封装
            $sdata['name'] = ['like','%'.$data['name'].'%'];
        }

        $deals =  $this->obj->getNormalDeals($sdata);
        $categorys = model("Category")->getNormalFristCategory();
        $categoryArrs= $cityArrs=[];
        foreach ($categorys as $category)
        {
            $categoryArrs[$category->id] =$category->name;
        }

        $citys=model("City")->getNormalCitys();

        foreach ($citys as $city)//城市id变为中午城市
        {
            $cityArrs[$city->id] =$city->name;
        }
        return $this->fetch('',[
            'categorys'=>$categorys,
            'citys'=>$citys,
            'deals'=>$deals,
            'category_id'=>empty($data['category_id'])?'':$data['category_id'],
            'city_id'=>empty($data['city_id'])?'':$data['city_id'],
            'name'=>empty($data['name'])?'':$data['name'],
            'start_time'=>empty($data['start_time'])?'':$data['start_time'],
            'end_time'=>empty($data['end_time'])?'':$data['end_time'],
            'cityArrs'=>$cityArrs,
            'categoryArrs'=>$categoryArrs
        ]);

    }

    public function add()
    {
        $citys = model('City')->getNormalFirstCity();
        $categorys = model('Category')->getFristCategorys();
        return $this->fetch('', [
            'citys' => $citys,
            'categorys' => $categorys,
            'bismen' => model('BisMen')->getNomalMenId($bisId),
        ]);
    }

    //修改状态
    public function status()
    {
        $data = input('get.');
        /*  $validate = validate('Bis');  //进行校验
          if(!$validate->scene('status')->check($data)){
              $this->error($validate->getError());
          }*/
        $res = $this->obj->save( ['status'=>$data['status']], ['id'=>$data['id']]);
        if($res){
            $this->success('状态更改成功');
        }else{
            $this->error('状态更改失败');
        }
    }
}
