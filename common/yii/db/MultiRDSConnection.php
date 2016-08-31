<?php
namespace common\yii\db;

class MultiRDSConnection extends \yii\db\Connection
{
	public $dbList = [];
	
	protected static $DBID = 1;
	
	public function setDbId($dbId)
	{
		if ($dbId !== static::$DBID)
		{
			static::$DBID = $dbId;
			$this->dsn = 'mysql:host=' . $this->dbList[$dbId]['host'] . ';dbname=' . $this->dbList[$dbId]['dbname'];
			$this->username = $this->dbList[$dbId]['username'];
			$this->password = $this->dbList[$dbId]['password'];
			$this->close();
		}
	}
	
	public function getDbId()
	{
		return static::$DBID;
	}
}