<?php
namespace app\index\controller;

use think\Controller;

class Detail extends Base
{

    public function detail($id)
    {
        if(!intval($id))
        {
            $this->error('id不合法');

        }

        //根据id查询商品的数据
        $deal = model('Deal')->get($id);
        $bisId=$deal->bis_id;
        $bis_account_data = model('Bis')->getBisShanghuDataId($bisId);
        if(!$deal|| $deal->status!=1)
        {
            $this->error('该商品不存在');
        }
            //获取分类信息
       $category=  model('Category')->get($deal->category_id);

        //获取分店信息
        $men =  model('BisMen')->getNormalMenInId($deal->location_ids);
        $title=  ($deal['name']);

        $flage = 0 ;
        if($deal->start_time>time())
        {
            $flage =1;
            $dtime = $deal->start_time - time();

            $timedata='';
            $d =floor($dtime/(3600*24));//向下摄入最近的一个整数  如 0.7 =0
            if($d)
            {
                $timedata.=$d."天";//获取多少天
            }

          $h =  floor($dtime%(3600*24)/3600);//还有多少小时
           if($h)
           {
               $timedata.=$h."小时";
           }

            $m =  floor($dtime%(3600*24)%3600/60);//还有多少分钟
            if($m)
            {
                $timedata.=$m."分";
            }

            $this->assign('timedata',$timedata);

        }

        print_r($men[0]['xpoint'].','.$men[0]['ypoint']);
        return $this->fetch('',[
            'title'=>$title,
            'category'=>$category,
            'men'=>$men,
            'deal'=>$deal,
            'onverplus'=>($deal->total_count-$deal->buy_count),
            'flage'=>$flage,
            'mapstr'=>$men[0]['xpoint'].','.$men[0]['ypoint'],
            'bis_account_data'=>$bis_account_data,
        ]);
    }
}
