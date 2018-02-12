<?php
namespace app\bis\controller;

use think\Controller;

class Register extends Controller
{
    public function index()
    {
        $citys = model('City')->getNormalFirstCity();
        $categorys = model('Category')->getFristCategorys();
        return $this->fetch('',[
            'citys'=>$citys,
            'categorys'=>$categorys
        ]);
    }
    public function add()
    {
        if(!request()->isPost())
        {
            $this->error('请求错误');
        }
        //获取表单得值

        $data = input('post.');//第三个参数旋转过滤数组的方式htmlentities 防止XSS脚本攻击  也可以在config中配置默认过滤方法

        //进行valdate校验
        $validate = validate('bis');
        if(!$validate ->scene('add')->check($data))
        {
            $this->error($validate->getError());
        }

        //获取经纬度
        $lnglat = \Map::getLat($data['address']);

        if(empty($lnglat) || $lnglat['status']!=0 || $lnglat['result']['precise']!=0) {
            $this->error('无法获取数据 ，获取匹配得地址有误');
        }

        //判断提交得用户是否存在
       $accountReult =   model('BisAccount')->get(['username' =>$data['username']]);

        if($accountReult)
        {
            $this->error('该用户也存在，请重新填写');

        }

        //商户信息入库
        $bisData=[
            'name' => $data['name'],//将填入的姓名转为实体呈现  防止XSS攻击
            'city_id' => $data['city_id'],
            'city_path'=>empty($data['se_city_id'])?$data['city_id']:$data['city_id'].','.$data['se_city_id'],
            'logo'=>$data['logo'],
            'licence_logo' => $data['licence_logo'],
            'description' =>empty($data['description'])?'':$data['description'],
            'bank_info'=>$data['bank_info'],
            'bank_user'=>$data['bank_user'],
            'bank_name'=>$data['bank_name'],
            'faren'=>$data['faren'],
            'faren_tel'=>$data['faren_tel'],
            'email'=>$data['email']
        ];

       $bisId =  model('Bis')->add($bisData);

       //总店相关信息入库

        $data['cat'] ='';
        if(!empty($data['set_category_id'])){
            $data['cat'] = implode('|',$data['ser_category_id']);//通过|进行连接
        }
        $locationData=[
            'bis_id' => $bisId,
            'name'=>$data['name'],
            'tel'=>$data['tel'],
            'logo'=>$data['logo'],
            'contact'=>$data['contact'],
            'category_id'=>$data['category_id'],
            'category_path'=>$data['category_id'].','.$data['cat'],
            'city_id'=>$data['city_id'],
            'city_path'=>empty($data['se_city_id'])?$data['city_id']:$data['city_id'].','.$data['se_city_id'],
            'address'=>$data['address'],
            'open_time'=>$data['open_time'],
            'content'=>empty($data['content'])?'':$data['content'],
            'is_main'=>1,//代表得是总店信息
            'xpoint'=>empty($lnglat['result']['location']['lng'])?'':$lnglat['result']['location']['lng'],//
            'ypoint'=>empty($lnglat['result']['location']['lat'])?'':$lnglat['result']['location']['lat'],//

        ];

        $locationid =  model('BisMen')->add($locationData);

        //账户相关得信息校验

        $data['code'] = mt_rand(100,10000);//自动生成密码加盐字符串
        $accounData=[
            'bis_id'=>$bisId,
            'username'=>$data['username'],
            'code'=>$data['code'],
            'password'=>md5($data['password'].$data['code']),
            'is_main'=>1,//代表总管理员

        ];
        $accountId = model('BisAccount')->add($accounData);

        if(!$accountId)
        {
            $this->error('申请失败');
        }

        $url = request()->domain().url('bis/register/waiting',['id'=>$bisId]);//点击得连接
        $title = '商户入驻通知';
        $content="您提交得入驻申请需等待平台方审核,您可以通过点击连接<a href='".$url."' target='_blank'>查看连接</a>查看审核状态";
        \phpmailer\Email::send($data['email'],$title,$content);

        //发送邮件

        $this->success('申请入驻成功',url('bis/register/waiting',['id'=>$bisId]));

    }

    public function waiting($id)
    {
        if(empty($id))
        {
            $this->error('error');
        }

       $detail =  model('Bis')->get($id);

        return $this->fetch('',[
            'detail' => $detail
        ]);
    }

}