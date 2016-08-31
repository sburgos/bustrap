<?php
namespace app\layouts\event;

use Yii;
use yii\base\Event;
use yii\helpers\Inflector;
use common\yii\helpers\AppHelper;
use common\yii\helpers\ArrayHelper;
use common\yii\helpers\Html;

class LayoutEvent extends Event
{
	const PREPARE_MENU = 'layout_event_prepare_menu';

	public $menuModules = [];
	
	public $menu = [];

	public $rightMenu = [];
	
	public function __construct($config = [])
	{
		// Build the menu modules items which include the
		// links to the first page of each module
		$items = [];

		foreach (Yii::$app->modules as $moduleId => $module)
		{
			
			$items[] = [
				'label' => ArrayHelper::getValueMixed($module, 'title', Inflector::camel2words($moduleId)),
				'url' => ["/{$moduleId}"],
			];
		}

		// Determine the name to display in the dropdown based on the
		// requested module.
		$rootModule = AppHelper::getRootModule();
		$this->menuModules = [
			'modulePicker' => [
				'label' => ArrayHelper::getValueMixed($rootModule, 'title', Inflector::camel2words($rootModule->id)),
				'items' => $items,
			],
		];

		
		
		// Add the right menu links
		if (!Yii::$app->user->isGuest)
		{
			$this->rightMenu = [
				'profile' => [
					'label' => Yii::$app->user->identity->username,
					'items' => [
						'profile-label' => "<li class='dropdown-header'>" . Html::encode(Yii::$app->user->identity->displayName) . "</li>",
						'change-password' => [
							'label' => "Change password",
							'url' => ['/admin/index/change-password'],
						],
						'logout-divider' => '<li class="divider"></li>',
                 		'logout' => [
							'label' => "Sign out",
							'url' => ['/admin/index/logout'],
							'linkOptions' => ['data-method' => 'post'],
						]
					], 
				]
			];
		}
	}
}