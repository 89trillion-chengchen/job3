<?php

namespace service;

use utils\Functions;

class MongodbService extends BaseService
{

    /**
     * MongodbService constructor.
     */
    public function __construct()
    {
        $this->dataDao = parent::__construct("dao\\MongodbDao");
    }

    public function setConf($name)
    {
        $this->dataDao->setConf($name);
    }

    public function save($collection, $data)
    {
        return $this->dataDao->save($collection, $data);
    }

    public function find($collection,$cond,$fields=array()){
        return $this->dataDao->find($collection,$cond,$fields=array());
    }

    public function seclectDB($DBname)
    {
        return $this->dataDao->seclectDB($DBname);
    }

    public function selectCollection($collection){
        return $this->dataDao->selectCollection($collection);
    }
}