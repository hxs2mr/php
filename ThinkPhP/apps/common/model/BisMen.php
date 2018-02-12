<?php
namespace app\common\model;

use think\Model;

class BisMen extends BaseModel
{

    public function getmenid($bis_id)
    {

        $data=[
            'status'=>['neq',2], //neq不等于-1
            'bis_id'=>$bis_id,
        ];

        $order=[
            'id'=>'desc',
        ];

        return $this->where($data)
                    ->order($order)
                    ->paginate(15);
    }



    public function getNomalMenId($bisId)
    {

        $data=[
            'bis_id'=>$bisId, //neq不等于-1
            'status'=>1,
        ];
        $order=[
            'id'=>'desc',
        ];

        return $this->where($data)
            ->order($order)
            ->paginate(15);
    }

    public function getNomalMenAll()
    {
        $order=[
            'id'=>'desc',
        ];

        return $this
            ->order($order)
            ->paginate(15);
    }

    public function getNormalMenInId($ids)
    {
        $data=[
          'id'=>['in',$ids],
            'status'=>1,
        ];
        return $this->where($data)
                    ->select();
    }

}