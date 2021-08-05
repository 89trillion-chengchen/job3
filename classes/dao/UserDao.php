<?php


namespace dao;


use framework\data\mongo\MongoHelper;

class UserDao extends ConstDaoBase
{
	/** @var MongoHelper */
	protected $mongoHelper;

	/**
	 * UserDao 构造函数
	 *
	 * @return
	 */
	public function __construct()
	{
		$this->useMongo();
	}

	public function cat()
	{
		return $this->mongoHelper->find("one");
	}

}