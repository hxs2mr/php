<?php
namespace app\bis\controller;


class Deal extends Base
{
    protected $obj;
    public function _initialize()//抽离出公用部分  并初始化
    {
        $this->obj = model("Deal");
    }

    public function index()
    {
        $bisId = $this->getLoginUser()->bis_id;
        $deals = $this->obj->getDealId($bisId);
        return $this->fetch('', [
            'deals'=>$deals
        ]);
    }
    public function add()
    {
        $bisId = $this->getLoginUser()->bis_id;
        if(request()->isPost())
        {
            //插入数据
            $data = input('post.');
            //严格校验提交的数据  validate

            $validate = validate('deal');
            if(!$validate->scene('add')->check($data))
            {
                return $this->error($validate->getError());
            }
            $lnglat = \Map::getLat($data['address']);
            if(empty($lnglat) || $lnglat['status']!=0 || $lnglat['result']['precise']!=0) {
                $this->error('门店地址输入有误');
            }
            $deals =[
              'bis_id'=>$bisId,
                'name'=>$data['name'],
                'image' => $data['image'],
                'category_id' =>$data['category_id'],
                'se_category_id' => empty($data['se_category_id'])?'':implode(',',$data['se_category_id']),
                'city_id' => $data['city_id'],
                'location_ids' => empty($data['location_ids'])?'':implode(',',$data['location_ids']),
                'start_time' => strtotime($data['start_time']),
                'end_time' => strtotime($data['end_time']),
                'total_count' => $data['total_count'],
                'origin_price' => $data['origin_price'],
                'current_price' => $data['current_price'],
                'coupons_begin_time' => strtotime($data['coupons_begin_time']),
                'coupons_end_time' => strtotime($data['coupons_end_time']),
                'notes' => $data['notes'],
                'description' => $data['description'],
                'bis_account_id' =>$this->getLoginUser()->id,
                'xpoint'=>empty($lnglat['result']['location']['lng'])?'':$lnglat['result']['location']['lng'],//
                'ypoint'=>empty($lnglat['result']['location']['lat'])?'':$lnglat['result']['location']['lat'],//
            ];

          $id =  model('Deal')->add($deals);
            if($id)
            {
                $this->success('添加成功',url('deal/index'));
            }else{
                $this->error('添加失败');
            }

        }else {
            $citys = model('City')->getNormalFirstCity();
            $categorys = model('Category')->getFristCategorys();
            return $this->fetch('', [
                'citys' => $citys,
                'categorys' => $categorys,
                'bismen' => model('BisMen')->getNomalMenId($bisId),
            ]);
        }
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
