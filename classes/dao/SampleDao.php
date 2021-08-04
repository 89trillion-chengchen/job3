<?php
/**
 *
 */

namespace dao;

class SampleDao extends ConstDaoBase
{

	protected $dbHelper;

	/**
	 * 构造函数
	 *
	 * @return
	 */
	public function __construct()
	{
		$this->useDb();
	}

    public function fetchAll($sql){
        $this->dbHelper->fetchAll($sql);
    }
}