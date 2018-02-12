<?php
/**
 * Created by PhpStorm.
 * User: microtech
 * Date: 2018/2/6
 * Time: 17:45
 */

namespace app\common\validate;


use think\Validate;

class Deal extends Validate
{
    protected  $rule=[
        'name'=> 'require|max:25',
        'category_id'=>'require',
        'image' => 'require',
        'city_id'=>'require',
        'start_time'=>'require',
        'end_time'=>'require',
        'total_count'=>'require|max:9999',
        'origin_price'=>'require',
        'current_price'=>'require',
        'coupons_begin_time'=>'require',
        'coupons_end_time'=>'require',
        ['address','require','门店地址不能为空'],
    ];

    //场景设置
    protected $scene=[
        'add'=>['name','category_id','image','city_id','start_time','end_time','total_count','origin_price','current_price','coupons_begin_time','coupons_end_time','address']
    ];

}