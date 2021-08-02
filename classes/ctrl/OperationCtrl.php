<?php


namespace ctrl;

use framework\util;
use service\AnswerService;
use service\CombatPointsService;
use service\OperationService;
use utils\HttpUtil;
use view\JsonView;


class OperationCtrl extends CtrlBase{


    /**
     * OperationCtrl constructor.
     */
    public function __construct()
    {
    }

    public function operation(){

        die('operation');

        //获取get或post请求数据
        $operation = HttpUtil::getPostData('operation');

        /** @var OperationService $operationService */
        $operationService = util\Singleton::get(OperationService::class);


        //校验数据
        list($checkResult, $checkNotice) = $operationService->checkUploadParams($operation);

        if (true!==$checkResult){
            $rspArr = AnswerService::makeResponseArray($checkNotice);
            return new JsonView($rspArr);
        }

        //执行函数
        $result=$operationService->getOperationResult($operation);

        return json_encode($result);

    }

}

try {
    if(!empty($_POST['exp'])){
        $exp=$_POST['exp'];
        //echo $exp;
        if(preg_match("/[\+\-\*\/\.]{2}|[^\+\-\*\/\(\)\d\.]+/i", $exp, $matches)){
            //echo '非法算式';
            return json_encode(array("message" => "非法算式"));
        }else{
            //echo '合法算式';
            $arr_exp = array();
            //将字符串保存进数组
            for ($i = 0; $i < strlen($exp); $i++) {
                $arr_exp[] = $exp[$i];
            }
            //反转数组
            $tmp=array_reverse($arr_exp);
            $main=new Main();
            $result = $main->calcexp($tmp);
            echo $exp . '=' . $result;
            return json_encode($exp . '=' . $result);
        }
    }else{
        return json_encode(array("message" => "请求参数为空"));
    }

}catch (Exception $e){
    $message=$e->getMessage();
    echo json_encode($message);
}


?>