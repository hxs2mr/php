<?php

namespace app\common\validate;
use think\Validate;
/**
 * Created by PhpStorm.
 * User: microtech
 * Date: 2018/2/5
 * Time: 12:22
 */

class User extends Validate{

    protected  $rule=[
        ['username', 'require|max:25','用户名不能为空'],
        ['email','email','邮箱格式不对'],
        ['password','require','密码不能为空'],
    ];

    //场景设置

    protected $scene=[
      'add'=>['username','email','password'],
      'login'=>['username','password'],
    ];

}