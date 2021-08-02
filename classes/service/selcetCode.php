<?php

include '../conf/redisConfig.php';
include '../conf/mysqlConfig.php';

class craetGift{

    /**
     *
     * @param $code
     */
    function creatGift($code){
        //获取redis连接
        $rediscon = new RedisCon();
        $redis = $rediscon->getRedis();
        //获取mysql连接
        $mysqlcon = new MysqlCon();
        $mysqlc = $mysqlcon->getMysql();




    }

}



?>
