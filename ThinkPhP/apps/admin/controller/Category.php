<?php
namespace app\admin\controller;

use think\console\command\make\Model;
use think\Controller;

class Category extends Controller
{
    protected $obj;
    public function _initialize()//抽离出公用部分  并初始化
    {
        $this->obj = model("Category");
    }
    public function index()
    {
        $parentId = input('get.parent_id',0,'intval');//参数1：get.获取对应的值  参数2：初始值, 参数3:对应的函数方法
        $categorys= $this->obj->getFristCategorys($parentId);
        return $this->fetch('',[
            'categorys'=>$categorys,
        ]);
    }
    public function add()
    {
        $categorys = $this->obj->getNormalFristCategory();

        //第一个参数默认是这个方法模板 第二个参数默认传递到这个参数中去   传递一级目录菜单 parent_id = 0 ;
        return $this->fetch('',[
            'categorys'=>$categorys,
        ]);
    }

    public function save()//先进行validate验证后  在调用model层的add()保存到数据库
    {
        //return $this->fetch();d
        //print_r(input('post.'));
        if(!request()->isPost())//判断是不是post请求
        {
            $this->error('请求失败');
        }
        $data = input('post.');//tp5自带获取post传递的数据
        //进行validate验证
       $vaildate= validate('Category');//Category代表验证的数据形式  在validate  Category.php文件中
       if(!$vaildate->scene('add')->check($data))//检测'添加'数据是不是为空  scene('listorder')//表示排序功能
       {
           $this->error($vaildate->getError());
       }

       if(!empty($data['id'])){//判断是添加还是编辑  id不为空就是编辑
           return $this->update($data);//跟新表单界面
       }

       //$data 提交到model层
        $res = $this->obj->add($data);
       if($res)
       {
           $this->success('添加成功');
       }else{
           $this->error('添加失败');
       }
    }
    public function edit()//编辑方法
    {
        $id = input('get.id');
        if(intval($id)<0)
        {
            $this->error('参数不合法');
        }
        $category =  $this->obj->get($id);//获取分类的这条内容

        //print_r($category);exit;
        //获取记录
        $categorys = $this->obj->getNormalFristCategory();

        //第一个参数默认是这个方法模板 第二个参数默认传递到这个参数中去   传递一级目录菜单 parent_id = 0 ;
        return $this->fetch('',[
            'categorys'=>$categorys,
            'category'=>$category,
        ]);
    }

    public function update($data)
    {
        $res  = $this->obj->save($data,['id'=>intval($data['id'])]);//跟新的数据 ，条件

        if($res)
        {
            $this->success('编辑成功');
        }else{
            $this->error('编辑失败');
        }
    }

    //排序
    public function listorder($id,$listorder)
    {
        $data = input('post.');//tp5自带获取post传递的数据
        $listorder = $data['listorder'];
        $id = $data['id'];
        //进行validate验证
        $vaildate= validate('Category');//Category代表验证的数据形式  在validate  Category.php文件中
        if(!$vaildate->scene('listorder')->check($data))//检测'添加'数据是不是为空  scene('listorder')//表示排序功能
        {
        // echo "<script>location.href='输入参数不合法';</script>";
            $this->error('输入参数不合法');
        }else{
            $res = $this->obj->save(['listorder' => $listorder], ['id' => $id]);
            if ($res) {
                $this->result($_SERVER['HTTP_REFERER'], 1, '排序成功');//抛出数据给js
            } else {
                $this->result($_SERVER['HTTP_REFERER'], 0, '排序失败');
            }
        }
    }
    //修改状态
    public function status()
    {
        $data = input('get.');
        $validate = validate('Category');
        if(!$validate->scene('status')->check($data)){
            $this->error($validate->getError());
        }
            $res = $this->obj->save( ['status'=>$data['status']], ['id'=>$data['id']]);
            if($res){
                $this->success('状态更改成功');
            }else{
                $this->error('状态更改失败');
            }
    }
}
