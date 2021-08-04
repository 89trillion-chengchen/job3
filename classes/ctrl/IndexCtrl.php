<?php
/**
 * @author: ChengRennt <ChengRennt@gmail.com>
 * @created: 2014-2-26 下午2:23:43
 * @description:
 * $Id: IndexCtrl.php 1261 2014-03-28 09:49:34Z pengcheng2 $
 */

namespace ctrl;

use dao\UserDao;
use framework\util;
use framework\util\Singleton;
use service\AnswerService;
use service\CacheService;
use service\GiftCodeService;
use service\IndexService;
use service\MongodbService;
use utils\HttpUtil;
use view\JsonView;
use MongoDB\Driver\Manager;

/**
 *
 */
class IndexCtrl extends CtrlBase
{
	/**
	 * 构造函数，继承父方法
	 *
	 * @return void
	 * @access public
	 */
	public function __construct()
	{
		parent::__construct();
	}

    public function index(){
        //获取get或post请求数据
        $uid=HttpUtil::getPostData('uid');

        /** @var IndexService $indexService */
        $indexService=Singleton::get(IndexService::class);

        //校验数据
        list($checkResult, $checkNotice) = $indexService->checkParam($uid);

        if (true!==$checkResult){
            $rspArr = AnswerService::makeResponseArray($checkNotice);
            return new JsonView($rspArr);
        }

        //执行函数
        $result=$indexService->creatUser($uid);

        return new JsonView($result);

    }

	public function test()
    {
        //die(sss);

        /*$mongo  = new \MongoClient('mongodb://10.0.3.53');

        $databas = $mongo->selectDB('job4');
        echo $databas;*/

        /** @var MongodbService $mongodbService*/
        $mongodbService = Singleton::get(MongodbService::class);
        $x=array(
            "金币"=>"67",
            "钻石"=>"645"
        );
        $result=$mongodbService->save("job4.runoob",$x);
            //$mongodbService->seclectDB('job4');


        //var_dump($result);


        //echo $db;
        /*$collection=$mongodbService->selectCollection('Collections');

        echo $collection;*/
        //$client = new Manager('mongodb://127.0.0.1:27017');


    }


}
