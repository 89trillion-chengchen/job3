<?php

namespace dao;

class MongodbDao extends ConstDaoBase{

    /**
     * MongodbDao constructor.
     */
    public function __construct()
    {
        $this->conf = 'default';
    }
    public function setConf($name)
    {
        $this->conf = $name;
    }

    public function save($collection,$data){

        $this->useMongo($this->conf);

        return $this->mongoHelper->save($collection,$data);
    }

    public function find($collection,$cond,$fields=array()){
        $this->useMongo($this->conf);

        return $this->mongoHelper->find($collection,$cond,$fields=array());
    }

    public function seclectDB($DBname){

        $this->useMongo($this->conf);

        return $this->mongoHelper->selcetDB($DBname);
    }

    public function selectCollection($collection){
        $this->useMongo($this->conf);

        return $this->mongoHelper->selectCollection($collection);
    }
}