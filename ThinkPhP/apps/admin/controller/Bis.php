<?php
namespace app\admin\controller;

use think\Controller;

class Bis extends Controller
{
    protected $obj;
    public function _initialize()//抽离出公用部分  并初始化
    {
        $this->obj = model("Bis");
    }

    //入驻申请列表
    public function apply()
    {
        $bis = $this->obj->getBisByStatus();

        return $this->fetch('', [
            'bis'=>$bis
        ]);
    }
    public function detail()
    {
        $id = input('get.id');
        if(empty($id))
        {
            return $this->error('id错误');
        }
        $citys = model('City')->getNormalFirstCity();
        $categorys = model('Category')->getFristCategorys();

        //获取商户数据
        $bisData = model('Bis')->get($id);//获取用户表中得数据

        $menData = model('BisMen')->get(['bis_id'=>$id,'is_main'=>1]);

        $accountData = model('BisAccount')->get(['bis_id'=>$id,'is_main'=>1]);
        return $this->fetch('',[
            'citys'=>$citys,
            'categorys'=>$categorys,
            'bisData'=>$bisData,
            'menData'=>$menData,
            'accountData'=>$accountData
        ]);
    }

    //修改状态
    public function status()
    {
        $data = input('get.');
      /*  $validate = validate('Bis');  //进行校验
        if(!$validate->scene('status')->check($data)){
            $this->error($validate->getError());
        }*/
        $res = $this->obj->save( ['status'=>$data['status']], ['id'=>$data['id']]);
        $men=  model('BisMen')->save(['status'=>$data['status']],['bis_id'=>$data['id']],'is_main=>1');
        $account=  model('BisAccount')->save(['status'=>$data['status']],['bis_id'=>$data['id']],'is_main=>1');
        $title ="商户入驻审核通知";
        $content ="";
        if($res && $men && $account){
            //发送邮件
            //根据status 1 2 -1
            $status = $data['status'];

            if($status==1)
            {
                print_r($status);
                $content ="恭喜您  你提交得入驻材料也审核成功  即将在本平台上架 请及时关注！";
                \phpmailer\Email::send('1363826037@qq.com',$title,$content);
            }else{
                $content ="对不起  你提交得入驻材料也未审核通过 请重新按要去严格提交材料";
                \phpmailer\Email::send('1363826037@qq.com',$title,$content);
            }
            $this->success('状态更改成功');
        }else{
            $this->error('状态更改失败');
        }
    }


    //商户列表
    public function index(){
        $bis = $this->obj->getBisShanghu(1);
        return $this->fetch('', [
            'bis'=>$bis
        ]);
    }

    //商户删除列表
    public function dellist(){
        $bis = $this->obj->getBisShanghu(2);
        return $this->fetch('', [
            'bis'=>$bis
        ]);
    }
}
