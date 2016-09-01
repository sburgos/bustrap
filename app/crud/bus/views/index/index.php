<?php

use common\yii\helpers\Html;
use yii\helpers\Url;
use common\yii\gii\generators\crud\event\CrudEvent;

Yii::$app->trigger(CrudEvent::RENDER_INDEX_PAGE_BEFORE, new CrudEvent(['moduleId' => $moduleId]));
echo "<div class='row'>";
foreach ($links as $link)
{
	$url = Url::toRoute($link['url']);
	if (!Yii::$app->user->can($url))
		continue;
	echo "<div class='col-xs-12 col-sm-4'>" . Html::a(
		$link['label'],
		$url, ['class'=>'btn btn-default btn-block', 'style' => 'text-align:left;margin-bottom:5px;']
	) . "</div>";
}
echo "</div>";
Yii::$app->trigger(CrudEvent::RENDER_INDEX_PAGE_AFTER, new CrudEvent(['moduleId' => $moduleId]));
