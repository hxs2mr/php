<?php
namespace app\common\model;

use think\Model;

class Deal extends BaseModel
{

    public function getDealId($bis_id)
    {
        $data=[
            'status'=>['neq',2],
            'bis_id'=>$bis_id,
        ];
        $order=[
            'id'=>'desc',
        ];

        return $this->where($data)
                    ->order($order)
                    ->paginate(15);
    }

    public function getNormalDeals($data=[])
    {
        $data['status'] = 1;

        $order=[
            'id'=>'desc',
        ];
        $reult = $this->where($data)
                        ->order($order)
                        ->paginate(15);

        //echo $this->getLastSql();
        return $reult;
    }

    //根据分类以及城市来获取商品的数据
    public  function getNormalDealCityId($id,$cityid,$limit =10)
    {
        $data=[
          'end_time'=>['gt',time()],
           'category_id'=>$id,
            'city_id'=>$cityid,
            'status'=>1,
        ];
        $order=[
            'listorder'=>'desc',
            'id'=>'desc',
        ];
        $result = $this->where($data)
                ->order($order);
        if($limit)
        {
            $result  = $result->limit($limit);
        }

        return     $result->select();
    }

}