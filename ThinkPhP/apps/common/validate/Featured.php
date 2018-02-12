<?php
namespace app\common\validate;
use think\Validate;

/**
 * Created by PhpStorm.
 * User: microtech
 * Date: 2018/2/7
 * Time: 12:23
 */
Class Featured extends Validate{

    protected $rule=[
        ['type','require','请选择推荐位分类'],
        ['title','require|max:100','标题过长'],
        ['image','require','图片不能为空'],
        ['url','require','请添加连接'],
        ['description','require','请添加描述'],
    ];
    protected $scene=[
        'add'=>['type','title','image','url','description'],
    ];
}