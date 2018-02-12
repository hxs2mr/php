<?php
namespace app\common\model;

use think\Model;

class Bis extends BaseModel
{

    public function getBisByStatus()
    {
        $order =[
            'id'=>'desc'
        ];
        $data =[
            'status'=>['neq',2], //neq不等于2
        ];

       return $this->where($data)
            ->order($order)
           ->paginate(15);//必须要设置分页才能有分页得效果 不能使用默认得  找不到
    }

    public function getBisShanghu($status=1)
    {
        $order =[
            'id'=>'desc'
        ];
        $data =[
            'status'=>$status, //neq不等于-1
        ];

        return $this->where($data)
            ->order($order)
            ->paginate(15);//必须要设置分页才能有分页得效果 不能使用默认得  找不到
    }

    public function getBisShanghuDataId($bisid)
    {
        $data =[
            'id'=>$bisid, //neq不等于-1
        ];

        return $this->where($data)
            ->find();
    }

}