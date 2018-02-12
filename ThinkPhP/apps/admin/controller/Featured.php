<?php
namespace app\admin\controller;

use think\Controller;

class featured extends Controller
{
    protected $obj;
    public function _initialize()//抽离出公用部分  并初始化
    {
        $this->obj = model("Featured");
    }

    public function add()
    {
        if(request()->isPost())
        {//入库
            $data = input('post.');
            //数据校验 validate

            $validate = validate('Featured');
            if(!$validate->scene('add')->check($data))
            {
                return $this->error($validate->getError());
            }
            $id  = model('Featured')->add($data);
            if($id)
            {
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }

        }else {
            $types = config('featured.featured_type');
            return $this->fetch('', [
                'types' => $types,
            ]);
        }
    }

    public function index()
    {
        $data = input('get.');
        $sdata=[];
        if(!empty($data['type']))
        {
            $sdata['type'] = $data['type'];
        }

        //获取推荐位类别
        $types = config('featured.featured_type');

        //获取列表数据

        $type=input('get.type','0'.'intval');

     //   $find_featureds = $this->obj->getNormalFeatured($sdata);

        $featureds =  $this->obj->getFeaturedsByType($type);

        return $this->fetch('',[
            'types'=>$types,
            'featured_key'=>$type,
            'featureds'=>$featureds,
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
