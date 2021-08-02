<?php
/**
 * @author: ChengRennt <ChengRennt@gmail.com>
 * @created: 2014-2-26 下午2:23:43
 * @description:
 * $Id: IndexCtrl.php 1261 2014-03-28 09:49:34Z pengcheng2 $
 */

namespace ctrl;

use framework\util;
use framework\util\Singleton;
use service\CacheService;

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

	public function main()
	{
		return '';
	}

	public function test()
    {

        $content=array("uqwyi" => "67", "qweqwe" => "645");
        /** @var CacheService $cacheService */
        $cacheService = Singleton::get(CacheService::class);
        /*$cacheService->lPush('code_79hsjakkh_use',json_encode($content));
        $liscc = $cacheService->getLists(code_79hsjakkh_use);*/

        //var_dump($cacheService->getAllHash(code_ye6vZ39W));

        $codeList=$cacheService->getAllHash(code_ye6vZ39W);
        $useList=$cacheService->getAllHash('code_ye6vZ39W'.'_use');

        print_r($codeList) ;
        print_r($useList) ;


    }
}
