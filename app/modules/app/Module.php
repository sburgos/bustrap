<?php
namespace modules\app;

use Yii;
use common\yii\gii\generators\crud\event\CrudEvent;
use yii\helpers\Url;
use yii\helpers\Html;
use orm\admin\SupportCaseType;

class Module extends \app\base\Module
{

	public function beforeAction($action)
	{
		$this->layout = 'app';
		return parent::beforeAction($action); // TODO: Change the autogenerated stub
	}
}