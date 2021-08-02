<?php
namespace service;

class OperationService extends BaseService {


    /**
     * CombatPointsService constructor.
     */
    public function __construct()
    {
    }

    public function checkUploadParams($params)
    {

        if (!isset($params) || empty($params)) {
            return [ false, 'lack_of_$params' ];
        }
        return [ true, 'ok' ];
    }

    public function getOperationResult($operation){
        try {

            if(preg_match("/[\+\-\*\/\.]{2}|[^\+\-\*\/\(\)\d\.]+/i", $operation, $matches) || !is_numeric(substr($operation,0,1)) || !is_numeric(substr($operation,-1))){
                    //echo '非法算式';
                    return
                        parent::show(
                        400,
                        'error',
                        $operation
                    );
                }else{
                    //echo '合法算式';
                    $arr_exp = array();
                    //将字符串保存进数组
                    for ($i = 0; $i < strlen($operation); $i++) {
                        $arr_exp[] = $operation[$i];
                    }
                    //反转数组
                    $tmp=array_reverse($arr_exp);

                    //得出结果
                    $result = $this->calcexp($tmp);

                    //echo $operation . '=' . $result;
                    return parent::show(
                        200,
                        'ok',
                        $operation . '=' . $result
                    );

                }
        }catch (Exception $e){
            $message=$e->getMessage();
            echo json_encode($message);
        }
    }



    function  calcexp($exp){

        $arr_n = array();
        $arr_op = array();

        while (($s = array_pop($exp)) != '') {

            if ($s == '*' || $s == '/') {
                $n2 = array_pop($exp);
                $op = $s;
                $n1 = array_pop($arr_n);
                $result = $this->operation($n1, $op, $n2);
                array_push($arr_n, $result);
            }else if ($s == '+' || $s == '-') {

                array_push($arr_op, $s);

            } else if (is_numeric($s)){
                array_push($arr_n, $s);
            }
        }
        $n2 = array_pop($arr_n);
        while (($op = array_pop($arr_op)) != '') {
            $n1 = array_pop($arr_n);
            $n2 = $this->operation($n1, $op, $n2);
        }
        return $n2;
    }

    function operation($n1, $op, $n2){

        switch ($op) {
            case '+':
                return $n1 + $n2;
                break;
            case '-':
                return $n1 - $n2;
                break;
            case '*':
                return $n1 * $n2;
                break;
            case '/':
                return intval($n1/$n2);
                break;
        }
    }





}