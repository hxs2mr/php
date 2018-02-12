<?php
namespace app\common\model;

use think\Model;

class User extends BaseModel
{


    public function add($data=[])
    {
        if(!is_array($data))
        {
            exception('传递的数据不是数组');//抛出异常
        }

        $data['status']=1;
        //allowField过滤掉数据表中没有的字段
        return  $this->data($data)->allowField(true)
                ->save();
    }


    /*
     * 根据用户名查看
     * */
    public function getUserByName($username)
    {
        if(!$username)
        {
            exception('用户名不合法');
        }

        $data =['username'=>$username];
        return $this->where($data)->find();//查找数据
    }

}