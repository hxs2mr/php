<?php
namespace app\common\model;

use think\Model;


//公共得model层
class BaseModel extends Model
{
    protected  $autoWriteTimestamp = true;//tp5中自动设置时间 create_time
    public function add($data){//model添加数据
        $data['status'] =0;
        //$data['create_time']=time();
        $this->save($data);//保存到数据库中
        return $this ->id;//返回主键id
    }

    public function updateById($data,$id)
    {
            return $this->allowField(true)->save($data,['id'=>$id]);
    }
}