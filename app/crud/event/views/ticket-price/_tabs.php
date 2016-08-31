<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use common\yii\gii\generators\crud\event\CrudEvent;

//
// List of tabs to display
//----------------------------------------------------------------------------
$tabs = [
	'view' => [ 'label' => 'Detail', 'active' => false, 'visible' => true, 'url' => Url::toRoute(array_merge(['view'], $_GET))],
	'view-ticket-price-pa' => ['label' => 'Tab: Ticket Price: Ticket Price Pa', 'active' => false, 'visible' => true, 'url' => Url::toRoute(array_merge(['view-ticket-price-pa'], $_GET))],
];
foreach ($tabs as $tabId => $tabInfo) {
	$tabs[$tabId]['active'] = \Yii::$app->requestedAction->id == $tabId || (\Yii::$app->requestedAction->id == "update" && $tabId == "view");
	$tabs[$tabId]['visible'] = (\Yii::$app->user->can($tabInfo['url']));
}

//
// Trigger the filter tabs before rendering
//--------------------------------------------------------------------------
$event = new CrudEvent([
	'model' => $model, 
	'tabs' => $tabs,
]);
Yii::$app->trigger(CrudEvent::FILTER_TABS, $event);

//
// Tab items
//----------------------------------------------------------------------------
?>
<ul class="nav nav-tabs crud-nav-tabs" role="tablist">
	<?php Yii::$app->trigger(CrudEvent::RENDER_VIEW_TABS_BEFORE, new CrudEvent(['model' => $model])); ?>
	<?php foreach ($event->tabs as $tabId => $tabInfo) : ?>
	<?php if (isset($tabInfo['visible']) && !$tabInfo['visible']) continue; ?>
	<?php $class = (isset($tabInfo['active']) && $tabInfo['active']) ? "active" : ""; ?>
	<li class="<?= $class ?>">
		<a href="<?= $tabInfo['url']; ?>"><?= $tabInfo['label'] ?></a>
	</li>
	<?php endforeach; ?>
	<?php Yii::$app->trigger(CrudEvent::RENDER_VIEW_TABS_AFTER, new CrudEvent(['model' => $model])); ?>
</ul>
