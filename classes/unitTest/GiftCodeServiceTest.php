<?php

require "../../lib/raftphp/framework/data/redis/RedisConfiguration.php";
require "../../lib/raftphp/framework/data/redis/RedisHelper.php";
require "../../lib/raftphp/framework/data/redis/RedisManager.php";
require "../dao/DaoBase.php";
require "../dao/ConstDaoBase.php";
require "../dao/CacheDao.php";
require "../service/BaseService.php";
require "../service/CacheService.php";
require "../service/GiftCodeService.php";
require "../../lib/raftphp/framework/util/Singleton.php";

use framework\util\Singleton;
use PHPUnit\Framework\TestCase;
use dao\DaoBase;
use dao\ConstDaoBase;
use dao\CacheDao;
use service\BaseService;
use service\CacheService;
use service\GiftCodeService;


class GiftCodeServiceTest extends TestCase
{

    public function testCreatGiftCode()
    {
        $admin = 'admin';
        $description = 'description';
        $count = 3;
        $begintime = '2021-07-28 19:48:03';
        $endtime = '2021-08-28 19:48:03';
        $content = '{"coin": "67", "diamond": "645","hero": "盖伦1","soldier": "投石车","props": "八十连抽"}';
        $type = 1;
        $role = 1;
        /** @var GiftCodeService $giftCodeService */
        $giftCodeService = Singleton::get(GiftCodeService::class);
        print_r($giftCodeService->creatGiftCode($admin, $description, $count, $begintime, $endtime, $content, $type, $role));
    }

    public function testUseCode()
    {
        $admin = 'zhangsan';
        $role = 1;
        $code = 'code_Nc97bCFX';
        /** @var GiftCodeService $giftCodeService */
        $giftCodeService = Singleton::get(GiftCodeService::class);
        print_r($giftCodeService->useCode($admin, $role, $code));
    }

    public function testGetCodeInfo()
    {
        $code = 'code_Nc97bCFX';
        /** @var GiftCodeService $giftCodeService */
        $giftCodeService = Singleton::get(GiftCodeService::class);
        print_r($giftCodeService->getCodeInfo($code));
    }
}
