<?php
namespace app\common\model;

use think\Model;

class Category extends Model
{
    protected  $autoWriteTimestamp = true;//tp5中自动设置时间 create_time
    public function add($data){//model添加数据
        $data['status'] =1;
        //$data['create_time']=time();
        return $this->save($data);//保存到数据库中
    }

    public function getNormalFristCategory()
    {
        $data =[
          'status'=>1,
          'parent_id'=>0,
        ];

        $order =[
            'id'=>'desc',
        ];

      return $this->where($data)
                  ->order($order)
                   ->select();//查询
    }

    public function getFristCategorys($parentId=0)
    {//获取数据库中一级目录
       $data = [
           'parent_id'=>$parentId,
           'status'=>['neq',-1], //neq不等于-1
       ];
       $order =[
           'listorder'=>'desc',
          'id'=>'desc',
       ];
        return $this->where($data)
                ->order($order)
                ->paginate();//分页每页显示15条

       //echo $this->getLastSql();//打印sql语句
       //return $result;
    }



    public function getNormalRegisterCategory($parentId=0)
    {
        $data =[
            'status'=>1,
            'parent_id'=>$parentId,
        ];

        $order =[
            'id'=>'desc',
        ];

        return $this->where($data)
            ->order($order)
            ->select();//查询
    }

    public function getNormalRecommendCategory($id,$limit=7)
    {
        $data=[
          'parent_id'=>$id,
          'status' =>1,
        ];
        $order=[
          'listorder'=>'desc',
          'id'=>'desc'
        ];

        $result =  $this->where($data)
                    ->order($order);

        if($limit) {
            $result = $result->limit($limit);
        }

        return $result->select();
    }

    public function getNormalIndexCategorys($ids)//获取分类的二级目录
    {
        $data=[
            'parent_id'=>['in',implode(',',$ids)],
            'status' =>1,
        ];
        $order=[
            'listorder'=>'desc',
            'id'=>'desc'
        ];

        $result =  $this->where($data)
            ->order($order)
            ->select();

        return $result;
    }

}