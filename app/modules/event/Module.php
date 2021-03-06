<?php
namespace modules\bus;

use Yii;
use common\yii\gii\generators\crud\event\CrudEvent;
use yii\helpers\Url;
use yii\helpers\Html;
use orm\admin\SupportCaseType;

class Module extends \app\base\Module
{
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