<?php

namespace service;

use conf\MysqlCon;
use conf\RedisCon;
use entity\codeModel;
use framework\util\Singleton;
use conf\mysqlConfig;
use utils\HttpUtil;

class GiftCodeService extends BaseService
{
    /**
     * GiftCodeService constructor.
     */
    public function __construct()
    {
    }

    public function checkUploadParams($admin, $description, $count, $begintime, $endtime, $content, $type)
    {
        if (!isset($admin) || empty($admin)) {
            return [false, 'lack_of_admin'];
        }
        if (!isset($description) || empty($description)) {
            return [false, 'lack_of_description'];
        }
        if (!isset($count) || empty($count)) {
            return [false, 'lack_of_count'];
        }
        if (!isset($begintime) || empty($begintime)) {
            return [false, 'lack_of_begintime'];
        }
        if (!isset($endtime) || empty($endtime)) {
            return [false, 'lack_of_endtime'];
        }
        if (!isset($content) || empty($content)) {
            return [false, 'lack_of_content'];
        }
        if (!isset($type) || empty($type)) {
            return [false, 'lack_of_type'];
        }
        return [true, 'ok'];
    }

    public function checkParams($admin, $code, $role)
    {
        if (!isset($admin) || empty($admin)) {
            return [false, 'lack_of_admin'];
        }
        if (!isset($code) || empty($code)) {
            return [false, 'lack_of_code'];
        }
        if (!isset($role) || empty($role)) {
            return [false, 'lack_of_role'];
        }

        return [true, 'ok'];
    }

    public function checkParam($code)
    {
        if (!isset($code) || empty($code)) {
            return [false, 'lack_of_code'];
        }
        return [true, 'ok'];
    }


    /**
     * 创建礼品码
     * @param $admin
     * @param $description
     * @param $count
     * @param $begintime
     * @param $endtime
     * @param $content
     * @param $type
     * @param $role
     * @return array
     */
    public function creatGiftCode($admin, $description, $count, $begintime, $endtime, $content, $type, $role)
    {
        //生成礼包码
        $code = $this->getRandomString(8);
        /** @var CacheService $cacheService */
        $cacheService = Singleton::get(CacheService::class);
        //查看礼包码是否已存在
        while ($cacheService->exists('code_' . $code) > 0) {
            $code = $this->getRandomString(8);
        }
        //创建时间
        $craetData = date('Y-m-d H:i:s');
        //已领取数量
        $receivedCount = 0;
        //写入redis
        $result1 = $cacheService->setHash('code_' . $code, 'creatTime', $craetData);
        $result2 = $cacheService->setHash('code_' . $code, 'admin', $admin);
        $result3 = $cacheService->setHash('code_' . $code, 'description', $description);
        $result4 = $cacheService->setHash('code_' . $code, 'count', $count);
        $result5 = $cacheService->setHash('code_' . $code, 'begin_time', $begintime);
        $result6 = $cacheService->setHash('code_' . $code, 'end_time', $endtime);
        $result7 = $cacheService->setHash('code_' . $code, 'receivedCount', $receivedCount);
        $result8 = $cacheService->setHash('code_' . $code, 'type', $type);
        $result9 = $cacheService->setHash('code_' . $code, 'role', $role);

        //$content = array("coin" => "67", "diamond" => "645","props"=>"十连抽券","hero"=>"狐狸","soldier"=>"弓箭手");
        $content = json_decode($content, true);
        foreach ($content as $key => $value) {
            $cacheService->setHash('code_' . $code, 'content_' . $key, $value);
        }
        if ($result1 > 0 && $result2 > 0 && $result3 > 0 && $result4 > 0 && $result5 > 0 && $result6 > 0 && $result7 > 0 && $result8 > 0 && $result9 > 0) {
            //设置过期时间
            $cacheService->expire('code_' . $code, strtotime($endtime) - strtotime($craetData));
        } else {
            return parent::show(
                400,
                'error',
                '创建失败！'
            );
        }
        return parent::show(
            200,
            'ok',
            'code_' . $code
        );

    }

    public function useCode($admin, $role, $code)
    {
        /** @var CacheService $cacheService */
        $cacheService = Singleton::get(CacheService::class);

        //获取礼品码redis数据
        $redisArray = $cacheService->getAllHash($code);

        $description = $cacheService->getHash($code, 'description');
        $count = $cacheService->getHash($code, 'count');
        $begintime = $cacheService->getHash($code, 'begin_time');
        $endtime = $cacheService->getHash($code, 'end_time');
        $type = $cacheService->getHash($code, 'type');
        $roled = $cacheService->getHash($code, 'role');
        $receivedCount = $cacheService->getHash($code, 'receivedCount');
        $nowData = date('Y-m-d H:i:s');
        //取出礼包奖励内容
        $content = array();
        foreach ($redisArray as $key => $value) {
            if (substr($key, 0, 8) == 'content_') {
                $content[substr($key, 8, strlen($key))] = $value;
            }
        }
        if (empty($redisArray)) {
            return parent::show(
                400,
                'error',
                '礼包码未找到！'
            );
        } else if (strtotime($nowData) - strtotime($begintime) > 0 && strtotime($nowData) - strtotime($endtime) < 0) {
            //指定用户一次性消耗
            if ($type == 1) {
                if ($role == $roled) {//判断是否为指定角色
                    if ($count > 0) {//礼包码可使用次数是否足够
                        //可领取次数减一
                        $cacheService->setHash($code, 'count', $count - 1);
                        //领取次数加一
                        $cacheService->setHash($code, 'receivedCount', $receivedCount + 1);
                        //增加领取记录
                        $cacheService->setHash($code . '_use', $admin, $nowData);
                        return parent::show(
                            200,
                            'ok',
                            $content
                        );
                    } else {
                        return parent::show(
                            200,
                            'ok',
                            '礼包码已被兑换！'
                        );
                    }
                } else {
                    return parent::show(
                        400,
                        'error',
                        '权限不足，无法兑换！'
                    );
                }

            } else if ($type == 2) {//不指定用户限制兑换次数
                if ($count > 0) {//礼包码可使用次数是否足够
                    //判断是否领取过
                    $useList = $cacheService->getAllHash($code . '_use');
                    foreach ($useList as $key => $value) {
                        if ($key == $admin) {
                            return parent::show(
                                200,
                                'ok',
                                '已兑换过！'
                            );
                        }
                    }
                    //可领取次数减一
                    $cacheService->setHash($code, 'count', $count - 1);
                    //领取次数加一
                    $cacheService->setHash($code, 'receivedCount', $receivedCount + 1);
                    //增加领取记录
                    $cacheService->setHash($code . '_use', $admin, $nowData);
                    return parent::show(
                        200,
                        'ok',
                        $content
                    );

                } else {
                    return parent::show(
                        200,
                        'ok',
                        '礼包码兑换次数已达到上限！'
                    );
                }
            } else if (type == 3) {//不限用户不限次数兑换
                //领取次数加一
                $cacheService->setHash($code, 'receivedCount', $receivedCount + 1);
                //增加领取记录
                $cacheService->setHash($code . '_use', $admin, $nowData);
                return parent::show(
                    200,
                    'ok',
                    $content
                );
            }


        }


    }

    function getCodeInfo($code)
    {
        /** @var CacheService $cacheService */
        $cacheService = Singleton::get(CacheService::class);
        $codeList = $cacheService->getAllHash($code);
        $useList = $cacheService->getAllHash($code . '_use');
        if (empty($codeList)) {
            return parent::show(
                400,
                'error',
                '礼包码未找到！'
            );
        } else {
            return parent::showResule(
                200,
                'ok',
                $codeList,
                $useList
            );
        }
    }

    /**
     * 生成随机数
     * @param $len
     * @param null $chars
     * @return string
     */
    function getRandomString($len, $chars = null)
    {
        if (is_null($chars)) {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        }
        mt_srand(10000000 * (double)microtime());
        for ($i = 0, $str = '', $lc = strlen($chars) - 1; $i < $len; $i++) {
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }


    public function test()
    {
        $re = $this->exists(code_EkoJDXUG);
        //die(t);
        echo $re;
        die($re);
    }
}