<?php
namespace app\common\model;
use think\Model;
/**
 * Created by PhpStorm.
 * User: microtech
 * Date: 2018/2/4
 * Time: 15:30
 */
class City extends Model {

    public function getNormalFirstCity($parentId=0)
    {
        //获取数据库中一级目录
        $data = [
            'status'=>1, //neq不等于-1
            'parent_id'=>$parentId,
        ];
        $order =[
            'id'=>'desc',
        ];

        echo $this->getLastSql();//打印sql语句
        return $this->where($data)
            ->order($order)
            ->paginate();//分页每页显示15条

        //return $result;
    }
    public function getNormalCitys()
    {
        $data=[
            'status'=>1,
            'parent_id'=>['gt',0],
        ];
        $order=[
            'id'=>'desc',
        ];

        return $this->where($data)
                ->order($order)
                ->select();
    }
}