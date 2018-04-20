<?php
// 本类由系统自动生成，仅供测试用途
namespace Api\Controller;
use Think\Controller;
class CategoryController extends PublicController {
	//***************************
	// 产品分类
	//***************************
    public function index(){
    	$list = M('category')->where('tid=1')->field('id,tid,name,initial')->select();
        $tcatList = M('category')->where('tid='.intval($list[0]['id']))->field('id,name,bz_1')->select();
        foreach ($tcatList as $k => $v) {
//           $tcatList[$k]['bz_1'] = __DATAURL__.$v['bz_1'];
            $tcatList[$k]['list'] = M('category')->where('tid='.intval($v['id']))->field('id,name,bz_1')->select();//三级目录
            foreach ($tcatList[$k]['list'] as $tk => $tv){
                //三级目录
                $tcatList[$k]['list'][$tk]['bz_1'] = $tv['bz_1'];
            }

        }

    	//json加密输出
		//dump($json);
		echo json_encode(array('status'=>1,'list'=>$list,'catList'=>$tcatList));
        exit();
    }

    //***************************
    // 产品分类
    //***************************
    public function getcat(){
        $catid = intval($_REQUEST['cat_id']);
        if (!$catid) {
            echo json_encode(array('status'=>0,'err'=>'没有找到产品数据.'));
            exit();
        }

        $tcatList = M('category')->where('tid='.intval($catid))->field('id,name,bz_1')->select();//二级目录
        foreach ($tcatList as $k => $v) {
//           $tcatList[$k]['bz_1'] = __DATAURL__.$v['bz_1'];
            $tcatList[$k]['list'] = M('category')->where('tid='.intval($v['id']))->field('id,name,bz_1')->select();//三级目录
            foreach ($tcatList[$k]['list'] as $tk => $tv){
                //三级目录
                $tcatList[$k]['list'][$tk]['bz_1'] = $tv['bz_1'];
            }

        }

        //json加密输出
        //dump($json);
        echo json_encode(array('status'=>1,'catList'=>$tcatList));
        exit();
    }





    /*
     * 通过catid获得下一层的目录
     *
     * */
    public function getNextCatogeryByCatid(){
        $catid = intval($_REQUEST['cat_id']);

//        dump($catid) ;
        $list = M('category')->where('tid='.$catid)->field('id,tid,name,initial,bz_1')->select();
        if (!$catid) {
            echo json_encode(array('status'=>0,'err'=>'没有找到目录数据.'));
            exit();
        }
        echo json_encode(array('status'=>1,'list'=>$list,'catList'=>$list));

    }


    //***************************
    // 产品分类
    //***************************
    /*public function getcat(){
        $catid = intval($_REQUEST['cat_id']);
        if (!$catid) {
            echo json_encode(array('status'=>0,'err'=>'没有找到产品数据.'));
            exit();
        }

        $catList = M('category')->where('tid='.intval($catid))->field('id,name,bz_1')->select();
        foreach ($catList as $k => $v) {
            $catList[$k]['bz_1'] = __DATAURL__.$v['bz_1'];
        }

        //json加密输出
        //dump($json);
        echo json_encode(array('status'=>1,'catList'=>$catList));
        exit();
    }*/

}