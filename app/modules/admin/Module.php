<?php
namespace modules\admin;

use Yii;
use common\yii\gii\generators\crud\event\CrudEvent;
use yii\helpers\Url;
use yii\helpers\Html;
use orm\admin\SupportCaseType;

class Module extends \app\base\Module
{
	/**
	 * Attach events
	 * 
	 * (non-PHPdoc)
	 * @see \app\base\Module::init()
	 */
	public function init()
	{
		parent::init();
		Yii::$app->on(CrudEvent::RENDER_VIEW_BUTTONS, [$this, 'renderCrudViewButtons']);
		Yii::$app->on(CrudEvent::FILTER_GRID_COLUMNS, [$this, 'filterGridColumns']);
	}

    public function filterGridColumns($event)
    {

    }
	/**
	 * Add buttons to the crud view's
	 * 
	 * @param unknown $event
	 */
	public function renderCrudViewButtons($event)
	{
		// Add a button to change a user's password or login as the user
		if ($event->modelClass == 'orm\admin\UserAdmin')
		{
			$url = Url::toRoute(array_merge(['/admin/user/login-as-user'], $_GET));
			if (\Yii::$app->user->can('/admin/user/login-as-user'))
			{
				echo Html::a(
					"Login as this user",
					$url,
					[
						'class' => 'btn btn-warning',
					]
				);
			}
			
			$url = Url::toRoute(array_merge(['/admin/user/change-password'], $_GET));
			if (\Yii::$app->user->can('/admin/user/change-password'))
			{	
				echo Html::a(
					"Change this user's password",
					$url,
					[
						'class' => 'btn btn-info',
					]
				);
			}
		}
		
		// Add a button to load geonames info
		/*if ($event->modelClass == 'orm\admin\Country')
		{
			if (\Yii::$app->user->can('/admin/geoname/index'))
			{
				$url = Url::toRoute(array_merge(['/admin/geoname/index'], $_GET));
				echo Html::a(
					\Yii::t("admin/crud", "Upload geoname's"),
					$url,
					[
						'class' => 'btn btn-warning',
					]
				);
			}
		}*/
	}
	
	/**
	 * Prepare the main menu for this module
	 * 
	 * (non-PHPdoc)
	 * @see \app\base\Module::prepareMenu()
	 */
	public function prepareMenu($event)
	{
		$event->menu = [
			'setup' => [
				'label' => 'Setup',
				'items' => [
					"<li class='dropdown-header'>Administradores</li>",
					['label' => 'Roles', 'url' => ['/admin/crud/role-admin/index']],
					['label' => 'Cuentas', 'url' => ['/admin/crud/user-admin/index']],
				],
			],
            'Eventos' => [
                'label' => 'Eventos',
                'items' => [
                    ['label' => 'Organizadores', 'url' => ['/event/crud/manager/index']],
                    ['label' => 'Eventos', 'url' => ['/event/crud/event/index']],
                    ['label' => 'Tickets', 'url' => ['/event/crud/ticket/index']],
                    ['label' => 'Asistentes', 'url' => ['/event/crud/assistant/index']],
                ],
            ],
		];
	}
}