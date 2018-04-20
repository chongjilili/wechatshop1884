<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }



    /*
     * 分类导入数据库
     *
     * */
    public function setCategory(){
        $firstcat =  json_decode($this->getCurl("http://api.jisuapi.com/car/brand?appkey=31f6c21753f22d58"),true);//一级分类
        $firstcat = $firstcat['result'];
        foreach ($firstcat as &$cat ){
            $cat['carid'] = $cat['id'];
            $cat['tid'] = 1;
            $cat['sort'] = 0;
            $cat['addtime'] = time();
            $cat['content'] = $cat['name'];
            $cat['bz_1'] = $cat['logo'];
            unset($cat['id']);
        }



        M('category')->addAll($firstcat);
        dump($firstcat);

    }


    /*
     * 二级三级分类导入数据库
     *
     * */
    public function setCategory2(){

        $url1="http://api.jisuapi.com/car/brand?appkey=31f6c21753f22d58";
        $url2 = "http://api.jisuapi.com/car/type?appkey=31f6c21753f22d58&parentid=";


        $carids = M('category')->field('carid')->where('tid = 1')->select();
        $carids = array_column($carids, 'carid');
//        dump($carids);


            foreach ($carids as $carid){
                $twocat =  json_decode($this->getCurl($url2.$carid),true);//二级分类
                $twocat = $twocat['result'];

                $tid = M('category')->field('id')->where('carid ='.$carid)->find();//fuqingid
//                    dump($tid);
                   /* array_column($fid, 'id');*/


                foreach ($twocat as &$cat ){
                    $cat['carid'] = $cat['id'];
                    $cat['tid'] = $tid['id'];
                    $cat['sort'] = 1;
                    $cat['addtime'] = time();
                    $cat['content'] = $cat['name'];
//                    $cat['bz_1'] = $cat['logo'];
                    unset($cat['id']);
                    $result = M('category')->add($cat);//二级分类
                    if($result){
                        // 如果主键是自动增长型 成功后返回值就是最新插入的值
                        $insertId = $result;
                        $thirdcat = $cat['list'];
                        foreach ($thirdcat as &$tcat){//三级分类
                            $tcat['carid'] = $tcat['id'];
                            $tcat['tid'] = $insertId;
                            $tcat['sort'] = 2;
                            $tcat['addtime'] = time();
                            $tcat['content'] = $tcat['name'];
                            $tcat['bz_1'] = $tcat['logo'];
                            unset($tcat['id']);
                            $result = M('category')->add($tcat);//三级分类
                        }





                    }






                }

            }


        /*



        M('category')->addAll($firstcat);
        dump($firstcat);*/

    }



    public function setCategory3(){
        set_time_limit(0);
        $url3 = "http://api.jisuapi.com/car/car?appkey=31f6c21753f22d58&parentid=";
        $tcat = M('category')->field('id,carid,sort')->where('sort = 2')->select();//三级目录
//        dump($tcat);

        /*
         *
         *
         *
         * {
         * [0] => array(3) {
                ["id"] => string(3) "175"
                ["carid"] => string(3) "220"
                ["sort"] => string(1) "2"
                }
              [1] => array(3) {
                ["id"] => string(3) "176"
                ["carid"] => string(3) "221"
                ["sort"] => string(1) "2"
                }
         * }
         *
         *
         *
         *
         *
         *["list"] => array(56) {
    [0] => array(8) {
      ["id"] => string(4) "2571"
      ["name"] => string(34) "2016款 Sportback 35TFSI 进取型"
      ["logo"] => string(58) "http://pic1.jisuapi.cn/car/static/images/logo/300/2571.jpg"
      ["price"] => string(8) "18.49万"
      ["yeartype"] => string(4) "2016"
      ["productionstate"] => string(6) "停产"
      ["salestate"] => string(6) "停销"
      ["sizetype"] => string(12) "紧凑型车"
    }
         * */
        foreach ($tcat as $tc){

            $forthcats =  json_decode($this->getCurl($url3.$tc['carid']),true);//四级分类
            $forthcats = $forthcats['result'];
            foreach ($forthcats['list'] as $fcat ){
                //遍历四级分类
                $fcat['carid'] = $fcat['id'];
                $fcat['tid'] = $tc['id'];
                $fcat['sort'] = 3;
                $fcat['addtime'] = time();
                $fcat['content'] = $fcat['name'];
                $fcat['bz_1'] = $fcat['logo'];
                unset($fcat['id']);
                $result = M('category')->add($fcat);//四级分类
            }


        }
    }







    public function getCurl($url){
    $ch = curl_init();

    //设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    //执行并获取HTML文档内容
    $output = curl_exec($ch);

    //释放curl句柄
    curl_close($ch);
    return $output;

}



}