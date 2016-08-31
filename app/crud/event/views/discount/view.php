<?php

use yii\helpers\Url;
use common\yii\helpers\Html;
use yii\widgets\DetailView;
use common\yii\gii\generators\crud\event\CrudEvent;

/* @var $this yii\web\View */
/* @var $model orm\event\Discount */

if (!isset($onlyDetail)) {
	$this->title = 'Discount' . ": " . $model->code;
	$this->params['breadcrumbs'][] = ['label' => 'Discounts', 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
}
?>

<?php
//
// Render title
//----------------------------------------------------------------------------
?>
<?php  if (!isset($onlyDetail)) : ?>
<div class="crud-page-header page-header">
	<div class="btn-group btn-group-sm" style="float:right;">
		<?php Yii::$app->trigger(CrudEvent::RENDER_VIEW_BUTTONS, new CrudEvent(['model' => $model])); ?>
		<?= Html::aACL('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::aACL('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
	</div>
	<h1><?= Html::encode($this->title) ?></h1>
</div>
<?php  endif; ?>

<?php
//
// Render child table tabs
//----------------------------------------------------------------------------
?>
<?php  if (!isset($onlyDetail)) : ?>
<?= $this->render('_tabs', [
	'model' => $model,
]) ?>
<?php  endif; ?>

<?php
//
// Render details
//----------------------------------------------------------------------------
?>
<?= DetailView::widget([
	'model' => $model,
	'template' => '<div class="form-group"><label class="col-sm-2 control-label">{label}:</label><div class="col-sm-10 form-control-static">{value}</div></div>',
	'options' => [
		'tag' => 'div',
		'class' => 'form-horizontal crud-view-detail',
	],
	'attributes' => [
		'id',
		'code',
		'discount',
		'toDate',
		'fromDate',
		'eventId',
	],
]) ?>


