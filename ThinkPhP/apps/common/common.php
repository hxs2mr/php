<?php
/**
 * $msg 待提示的消息
 * $icon 这里主要有两个，5和6，代表两种表情（哭和笑）
 * $time 弹出维持时间（单位秒）
 */
function alert_error($msg = '', $time = 3)
{
    $str = '<script type="text/javascript" src="__STATIC__/admin/hui/static/lib/jquery/1.9.1/jquery.min.js"></script> <script type="text/javascript" src="__STATIC__/admin/hui/static/lib/layer/2.4/layer.js"></script>';//加载jquery和layer
    $str .= '<script>
         $(function(){
         layer.msg("' . $msg . '",{icon:"5",time:' . ($time * 1000) . '});
            setTimeout(function(){
          window.history.go(-1);},2000)
    });
  </script>';//主要方法

}