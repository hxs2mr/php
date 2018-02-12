<?php
namespace app\index\controller;

use think\Controller;

class Lists extends Base
{
    public function lists()
    {

        //首先获取一级栏目

        $firstCatIds = [];
        $categorys = model("Category")->getFristCategorys();
        foreach ($categorys as $category)
        {
            $firstCatIds[] = $category->id;
        }

        $id = input('id',0,'intval');
        $father_id = 0 ;
        if(in_array($id,$firstCatIds)){//一级分类
            //todo
            $father_id = $id;
        }else if($id){//二级分类
            //获取二级分类的相关数据
            $category = model('Category')->get($id);
            if(!$category||$category->status!=1){
                $this->error('数据不合法！');
            }
            $father_id = $category->parent_id;
        }else{
            $father_id = 0;
        }
        //获取父类下的所有 子分类
        $sedcategorys=[];
        if($father_id){
            $sedcategorys = model('Category')->getFristCategorys($father_id);
        }

        return $this->fetch('',[
            'categorys'=>$categorys,
            'sedcategorys'=>$sedcategorys,
            'id'=>$id,
            'father_id'=>$father_id,
        ]);
    }
}
