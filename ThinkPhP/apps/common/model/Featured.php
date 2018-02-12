<?php
namespace app\common\model;

use think\Model;

class Featured extends BaseModel
{

public function getFeaturedsByType($type)
    {
        $data=[
          'type'=>$type,
            'status'=>['neq',-1]
        ];
        $order=[
          'id'=>'desc'
        ];

        return $this->where($data)
                    ->order($order)
                    ->paginate(15);//分页
    }

    public function getNormalFeatured($data=[])
    {
        $data = ['status'=>['neq',-1]];
        $order=[
            'id'=>'desc',
        ];
        $reult = $this->where($data)
            ->order($order)
            ->paginate(15);

        //echo $this->getLastSql();
        return $reult;
    }

    public function getFeaturedIndexId($type =0)
    {
        $data=[
            'type'=>$type,
            'status'=>1,
        ];
        $order=[
            'id'=>'desc',
        ];

        return $this->where($data)
            ->order($order)
            ->select();
    }
}