<?php
/**
 * Created by PhpStorm.
 * User: microtech
 * Date: 2018/2/1
 * Time: 11:49
 */
namespace app\admin\validate;

use think\Validate;

class Category extends Validate{
    protected  $rule=[//设置form提交过来的数据进行处理
        ['name','require|max:10','分类名必须传递|分类名不能超过10个字符'],
        ['parent_id','number'],
        ['id','number'],
        ['status','number|in:-1,0,1','状态必须是数字|状态范围不合法'],  //in:-1,0,1表示范围
        ['listorder','number','输入参数不合法'],
    ];

    /*场景设置*/
    protected  $scene=[
      'add'=>['name','parent_id','id'],//添加功能场景 要用到的那些字段  有id值才验证
       'listorder'=>['id','listorder'],//排序 要用到的字段
        'status'=>['id','status'],//修改状态场景
    ];

}