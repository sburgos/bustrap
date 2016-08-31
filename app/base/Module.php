<?php
namespace app\base;

use Yii;
use yii\filters\AccessControl;
use yii\di\Instance;
use app\models\User;
use yii\web\ForbiddenHttpException;
use common\yii\helpers\Html;
use app\layouts\event\LayoutEvent;
use yii\bootstrap\Nav;
use common\yii\helpers\StringHelper;
use common\yii\helpers\AppHelper;
use yii\helpers\Inflector;

class Module extends \yii\base\Module
{
	public $title = null;
	
	public $defaultRoute = 'index/index';
	
	/**
	 * Init the module
	 * (non-PHPdoc)
	 * @see \yii\base\Module::init()
	 */
	public function init()
	{
		parent::init();
		Yii::$app->on(LayoutEvent::PREPARE_MENU, [$this, 'prepareMainMenu']);
	}
	
	/**
	 * Render the main menu
	 * @param unknown $event
	 */
	public function prepareMainMenu($event)
	{
		// Determine the name to display in the dropdown based on the
		// requested module.
		$rootModule = AppHelper::getRootModule();
		
		// Make sure that we are in the correct module
		if (!$rootModule || $rootModule->id != $this->id)
			return;
		
		// Call the prepare menu
		$this->prepareMenu($event);
	}
	
	/**
	 * Prepare the menu items by modifying the $event->menu and the
	 * $event->rightMenu. You can also change the $event->menuModules
	 * if you wish
	 * 
	 * @param $event \app\layouts\event\LayoutEvent
	 */
	public function prepareMenu($event)
	{
	}
}