<?php
namespace app\bis\controller;


class men extends Base
{
    protected $obj;
    public function _initialize()//抽离出公用部分  并初始化
    {
        $this->obj = model("BisMen");
    }

    public function add()
    {
        if(request()->isPost())
        {
            //进行tp5的validata验证

            $data = input('post.');

            $validate = validate('men');
            if(!$validate->scene('add')->check($data))
            {
                return $this->error($validate->getError());
            }

            $bisId = $this->getLoginUser()->bis_id;

            $data['cat'] ='';
            if(!empty($data['set_city_id'])){
                $data['cat'] = implode('|',$data['ser_category_id']);//通过|进行连接
            }

            //获取经纬度
            $lnglat = \Map::getLat($data['address']);

            if(empty($lnglat) || $lnglat['status']!=0 || $lnglat['result']['precise']!=0) {
                $this->error('无法获取数据 ，获取匹配得地址有误');
            }
            $data['cat1'] ='';
            if(!empty($data['set_category_id'])){
                $data['cat'] = implode('|',$data['ser_category_id']);//通过|进行连接
            }
            $locationData=[
                'bis_id' => $bisId,
                'name'=>$data['name'],
                'tel'=>$data['tel'],
                'logo'=>$data['logo'],
                'contact'=>$data['contact'],
                'category_id'=>$data['category_id'],
                'category_path'=>$data['category_id'].','.$data['cat1'],
                'city_id'=>$data['city_id'],
                'city_path'=>empty($data['se_city_id'])?$data['city_id']:$data['city_id'].','.$data['se_city_id'],
                'address'=>$data['address'],
                'open_time'=>$data['open_time'],
                'content'=>empty($data['content'])?'':$data['content'],
                'is_main'=>0,//代表得是分店信息
                'xpoint'=>empty($lnglat['result']['location']['lng'])?'':$lnglat['result']['location']['lng'],//
                'ypoint'=>empty($lnglat['result']['location']['lat'])?'':$lnglat['result']['location']['lat'],//

            ];
            $locationid =  model('BisMen')->add($locationData);
            if($locationid){
                return $this->success('门店申请成功');

            }else{
                return $this->error('门店申请失败');
            }
        }else{
        $citys = model('City')->getNormalFirstCity();
        $categorys = model('Category')->getFristCategorys();
        return $this->fetch('',[
            'citys'=>$citys,
            'categorys'=>$categorys
        ]);
        }
    }

    public function index()
    {
        $bisId = $this->getLoginUser()->bis_id;
        $bismen = $this->obj->getmenid($bisId);
        return $this->fetch('', [
            'bismen'=>$bismen
        ]);
    }
}
