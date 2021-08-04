<?php

namespace service;

class IndexService extends BaseService {

    /**
     * IndexService constructor.
     */
    public function __construct()
    {
    }

    public function checkParam($uid)
    {
        if (!isset($uid) || empty($uid)) {
            return [false, 'lack_of_uid'];
        }
        return [true, 'ok'];
    }

    public function creatUser($uid){

    }


}
