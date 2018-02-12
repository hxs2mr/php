<?php
namespace app\index\controller;

use think\Controller;

class Base extends Controller
{

    public $city = '';
    public $account = '';

    public function _initialize()
    {
        //城市数据
        $citys = model('City')->getNormalCitys();
        //用户数据
        $this->getCity($citys);
        $cats = $this->getRecommendCats();

        $this->assign('citys', $citys);//传递到模板
        $this->assign('city', $this->city);
        $this->assign('user', $this->getLoginUser());
        $this->assign('cats', $cats);
        $this->assign('controler', strtolower(request()->controller()));//获取是那个控制器  用来动态加载css
        $this->assign('title','荷包网');
        //获取首页分类的数据

    }

    public function index()
    {
        return $this->fetch();
    }
    public function getCity($citys)//获取初始的城市
    {
        $defaultuname = "";
        foreach ($citys as $city) {
            $city = json_decode(json_encode($city), true);
            $is_default = $city['is_default'];
            if ($is_default == 1) {
                $defaultname = $city['uname'];
                break;
            }
        }

        $defaultuname = $defaultuname ? $defaultuname : 'beijin';
        if (session('cityuname', '', 'o2o') && !input('get.city')) {
            $cityuname = session('cityuname', '', 'o2o');
        } else {
            $cityuname = input('get.city', $defaultuname, 'trim');
            session('cityuname', $cityuname, 'o2o');//放入session
        }

        $this->city = model('City')->where(['uname' => $cityuname])->find();

    }

    public function getLoginUser()//获取用户数据
    {
        if (!$this->account) {
            $this->account = session('o2o_user', '', 'o2o');
        }
        return $this->account;
    }


    public function getRecommendCats()//获取首页推荐中的商品分类数据
    {
        $parentIds  = $recomres = [];
        $sedResArr=[];
        //获取一级分类的数据
        $res = model('Category')->getNormalRecommendCategory(0, 7);
        foreach ($res as $car) {
            $parentIds[] = $car->id;
        }

        //获取二级分类的数据
        $sedRes = model('Category')->getNormalIndexCategorys($parentIds);
        foreach ($sedRes as $sedres) {
            $sedResArr[$sedres->parent_id][] = [
                'id' => $sedres->id,
                'name' => $sedres->name,
            ];
        }

        foreach ($res as $car) {
            //$recomres 一级分类+二级分类
            $recomres[$car->id] = [$car->name, empty($sedResArr[$car->id])?
            []:$sedResArr[$car->id]];
        }

        return $recomres;
    }
}