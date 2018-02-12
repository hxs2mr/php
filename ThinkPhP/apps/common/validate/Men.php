<?php
/**
 * Created by PhpStorm.
 * User: microtech
 * Date: 2018/2/6
 * Time: 16:06
 */
namespace app\common\validate;

use think\Validate;

class Men extends Validate
{

    protected $rule=[
        'name'=>'require|max:25',
        'email'=>'email',
        'logo'=>'require',
        'city_id'=>'require',
        'category_id'=>'require',
        'open_time'=>'require',
    ];

    protected $scene=[
        'add'=>['name','email','logo','city_id','category_id','open_time']
    ];
}