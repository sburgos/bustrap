<?php
namespace modules\events;

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
			'Eventos' => [
				'label' => 'Gestionar Eventos',
				'items' => [
					['label' => 'Eventos', 'url' => ['/events/event/index']],
					['label' => 'Tickets', 'url' => ['/events/ticket/index']],
					['label' => 'Asistentes', 'url' => ['/events/assistant/index']],
				],
			],
		];
	}
}