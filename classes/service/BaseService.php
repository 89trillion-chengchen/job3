<?php

namespace service;
use entity;
use dao;
use framework\util;

class BaseService
{

    protected $dataDao;

    protected function __construct($className)
    {
        $this->dataDao = util\Singleton::get($className);
        return $this->dataDao;
    }

    function show($status,$msg='',$data=[]) {

        $result = [
            'status'=>intval($status),
            'msg'=>$msg
        ];

        if(!empty($data)|| $data == 0){
            $result['data'] = $data;
        }

        return $result;
    }

    function showResule($status,$msg='',$codeInfo=[],$useList=[]) {

        $result = [
            'status'=>intval($status),
            'msg'=>$msg
        ];

        if(!empty($codeInfo)|| $codeInfo == 0){
            $result['codeInfo'] = $codeInfo;

        }
        if(!empty($useList)|| $useList == 0){
            $result['useList'] = $useList;

        }

        return $result;
    }


}


?>
