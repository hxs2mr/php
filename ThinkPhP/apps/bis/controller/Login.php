<?php
namespace app\bis\controller;

use think\Controller;

class Login extends Controller
{
    public function index()
    {
        if(request()->isPost())
        {
            //登录的逻辑
            //获取相关的数据
            $data = input('post.');

            //校验volidate
            //
           $ret  = model('BisAccount')->get(['username'=>$data['username']]);
           if(!$ret||$ret->status!=1)
           {
               $this->error('该用户不存在或该用户未审核通过');
           }

           if($ret->password !=md5($data['password'].$ret->code))
           {
               $this->error('密码不正确');
           }
           model('BisAccount')->updateById(['last_login_time'=>time()],
               $ret->id);

           //保存用户信息  bis是作用域
            session('bisAccount',$ret,'bis');//保存用户信息

            return $this->success("登录成功",url('index/index'));

        }else{
            //获取session
            $account = session('bisAccount','','bis');
            if($account && $account->id)
            {
                return $this->redirect(url('index/index'));//跳转
            }
            return $this->fetch();
        }

    }

    //退出登录
    public function logout()
    {
        //清除session
        session(null,'bis');//清除bis作用域下的所有session

        //跳转

        return $this->redirect(url('login/index'));
    }


}