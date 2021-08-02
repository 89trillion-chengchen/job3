<?php

namespace service;

use framework\mvc\view\JSONView;
use framework\util\Singleton;
use service\GiftCodeService;
use PHPUnit\Framework\TestCase;


class GiftCodeServiceTest extends TestCase
{

    public function testCreatGiftCode()
    {

        $admin='admin';
        $description='description';
        $count=3;
        $begintime='2021-07-28 19:48:03';
        $endtime='2021-08-28 19:48:03';
        $content='{"jinbi"=>"67","zuoshi"=>"645"}';
        $type=2;
        $role=1;
        $code='code_Eao9zb6x';


        /** @var GiftCodeService $giftCodeService */
        $giftCodeService = Singleton::get(GiftCodeService::class);
        echo new JSONView($giftCodeService->creatGiftCode($admin, $description, $count, $begintime, $endtime, $content, $type, $role));
        echo new JSONView($giftCodeService->getCodeInfo($code));
        echo new JSONView($giftCodeService->useCode($admin,$role,$code));

    }
}
