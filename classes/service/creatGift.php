<?php
include '../conf/redisConfig.php';
include '../conf/mysqlConfig.php';

class Gift
{
    private $redisCon;

    /**
     * 创建礼品码
     * @param $admin 创建人
     * @param $description 描述
     * @param $count 可领取次数
     * @param $begintime 生效时间
     * @param $endtime 失效时间
     * @param $content 礼包内容
     * @param $type 礼品码类型
     * @return string
     */
    function creatgiftNum($admin, $description, $count, $begintime, $endtime, $content, $type)
    {
        //获取redis连接
        $this->redisCon = '\RedisCon';
        $rediscon = new $this->redisCon();
        $redis = $rediscon->getRedis();
        //获取mysql连接
        $mysqlcon = new MysqlCon();
        $mysqlc = $mysqlcon->getMysql();

        //生成礼包码
        $gift = new Gift();
        $code = $gift->getRandomString(8);

        //查看礼包码是否已存在
        while ($redis->exists('code_' . $code)!='code_'.$code) {
            $code = $gift->getRandomString(8);
        }

        //创建时间
        $craetData = date('Y-m-d H:i:s');
        echo $craetData . "<br>";
        //已领取数量
        $receivedCount = 0;

        //写入redis
        $result1 = $redis->hSet('code_' . $code, 'begin_time', $begintime);
        $result2 = $redis->hSet('code_' . $code, 'en_time', $endtime);
        $result3 = $redis->hSet('code_' . $code,'count', $count);
        $result4 = $redis->hSet('code_' . $code,'receivedCount', $receivedCount);


        if ($result1 > 0&&$result2 > 0&&$result3 > 0&&$result4 > 0) {
            echo 'redis存储成功';
        }
        //写入mysql
        $sql = "INSERT INTO Code (giftCode, creatTime, admin,description,count,beginTime,endTime,receivedCount,type)
        VALUES ('$code','$craetData','$admin','$description','$count','$begintime','$endtime','$receivedCount','$type')";


        //$result = $mysqlc->query($sql);
        //echo $result."<br>";
        if ($mysqlc->query($sql) === TRUE) {
            echo "新记录插入成功";
        } else {
            echo "Error: " . $sql . "<br>" . $mysqlc->error;
        }



        $content=array("金币"=>"67","钻石"=>"645");

        foreach ($content as $key=>$value){
            $sql = "INSERT INTO gift (code,giftName, number)
            VALUES ('$code','$key','$value')";
            if ($mysqlc->query($sql) === TRUE) {
                echo "新记录插入成功";
            } else {
                echo "Error: " . $sql . "<br>" . $mysqlc->error;
            }

        }
        echo $code;
        return $code;
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
}



?>
