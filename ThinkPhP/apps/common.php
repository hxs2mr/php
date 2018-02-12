<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//用于显示-1 0 -1对应的中午
function status($status){
    if($status == 1){
        $str="<span class='label label-success radius'>正常</span>";
    }else if($status == 0)
    {
        $str="<span class='label label-warning radius'>待审</span>";
    }else{
        $str="<span class='label label-danger radius'>删除</span>";
    }
    return $str;
}

function is_main($is_main)
{
    if($is_main==1)
    {
        $str ="<span class='label label-success radius'>总店</span>";
    }else{
        $str="<span class='label label-warning radius'>分店</span>";
    }
    return$str;
}


/*
 *
 *
 * type  0 表示get  1 表示post
 *
 * */
function doCurl($url,$type=0,$data=[])
{

   $ch = curl_init();//初始化

    //设置选项
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//如果成功只返回结果
    curl_setopt($ch,CURLOPT_HEADER,0);//把一个headr数出来 为0 不需要数出来
    if($type==1)
    {
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    }
    //执行并获取内容

   $output = curl_exec($ch);
    return $output;
}

//商户申请后查看状态

function bisRegister($status)
{
    if($status == 1)
    {
        $str ='入驻申请成功';

    }else if($status == 0)
    {
        $str ='入驻正在审核,审核后平台会发送邮件通知';
    }else{
        $str ='非常抱歉，您提交得材料不符合条件请重新提交';
    }

    return $str;
}

function getDetailCityName($path)//设置为公共得方法获取城市目录
{
    if(empty($path))
    {
        return '';
    }
    if(preg_match('/,/',$path))
    {
        $citypath = explode(',',$path);
        $cityId = $citypath[1];
    }else{
        $cityId = $path;
    }

    $city = model('City')->get($cityId);
    return $city->name;
}

function countMen($ids)
{
    if(!$ids)
    {
        return 1;
    }
    if(preg_match('/,/',$ids))
    {
        $arr = explode(',',$ids);
        return count($arr);
    }
}