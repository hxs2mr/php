<?php
/**
 * Created by PhpStorm.
 * User: microtech
 * Date: 2018/1/31
 * Time: 16:10
 */

namespace app\index\controller;

use think\Controller;
use think\Exception;

class User extends Controller
{
    public function login()
    {
        //获取session 判断用户是否登录

        $user = session('o2o_user','','o2o');
        if($user && $user->id){
            $this->redirect(url('index/index'));
        }else{
            return $this->fetch();
        }
    }
    public function register()
    {

        if(request()->isPost()) {

            $data = input('post.');
            if (!captcha_check($data['verifycode']))//验证码校验
            {
                //校验失败
                $this->error('验证码不正确');
            } else {
                echo 'success';
            }
            //print_r($data);exit;
            //进行validate校验

            $validate = validate('User');
            if (!$validate->scene('add')->check($data))
            {
                return $this->error($validate->getError());
            }

            if($data['password']!=$data['repassword'])
            {
                $this -> error('两次密码输入不一致');
            }
            //其他的验证

            $data['code'] = mt_rand(100,10000);//自动生成密码加盐字符串
            $data['password']=   md5($data['password'].$data['code']);

            try{//重model user中捕获异常
             $res =  model('user')->add($data);
            }catch (Exception $e)
            {
                $data_err=$e->getMessage();
                    if(strpos($data_err, 'username'))
                    {
                        $this->error('该用户名也存在');
                    }else  if(strpos($data_err, 'email')){
                        $this->error('该邮箱也存在');
                    }else{
                        $this->error('注册失败 请稍后再试');
                    }
            }
            if($res)
            {
                $this->success('注册成功',url('user/login'));
            }else{
                $this->success('注册失败');
            }
        }else{

        }
        return $this->fetch();
    }

    public function logincheck()
    {
            if(!request()->isPost())
            {
                $this->error('提交不合法');
            }

            $data = input('post.');

            //校验volidate
            $validate = validate('User');
            if (!$validate->scene('login')->check($data))
            {
                return $this->error($validate->getError());
            }

            try{
                $user =  model('User')->getUserByName($data['username']);
            }catch (Exception $e)
            {
                $this->error($e->getMessage());
            }
            //判断用户名
            if(!$user||$user->status!=1)
            {
                $this->error('该用户不存在');
            }
            //判断密码

            if(md5($data['password'].$user->code)!=$user->password)
            {
                $this->error('密码不正确');
            }

            //登录成功

             model('user')->updateById(['last_login_time'=>time()],$user->id);

            //把用户信息记录到session

            session('o2o_user',$user,'o2o');

            $this->success('登录成功',url('index/index'));

    }

    public function logout()
    {
        //清空session
        session(null,'o2o');
        $this->redirect(url('user/login'));
    }
}