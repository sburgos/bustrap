<?php

use yii\helpers\Url;
use common\yii\helpers\Html;
use common\yii\helpers\StringHelper;
use common\yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\yii\gii\generators\crud\event\CrudEvent;

/* @var $this yii\web\View */
/* @var $model orm\event\TicketLog */

$this->title = 'Ticket Log' . ": " . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Ticket Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
//
// Render title
//----------------------------------------------------------------------------
?>
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

<?php
//
// Render child table tabs
//----------------------------------------------------------------------------
?>
<?= $this->render('_tabs', [
	'model' => $model,
]) ?>


<?php
//
// Render the grid
//---------------------------------------------------------------------------- ?>
<?php
	$path = "../" .  Inflector::camel2id($refClass) ;
	$urlPath =  Inflector::camel2id($refClass) ;
	
	if (empty($oneModel))
	{
		?>
		No data. <button type="button" class="btn btn-success" onclick="$('.crud-modal-add').modal();"><?= 'Add' ?></button>
		<?php
	}
	else
	{
		?>
		<div class="crud-page-header page-header">
			<div class="btn-group btn-group-sm" style="float:right;">
			<?php
			echo Html::aACL('Edit', [$urlPath . '/update', 'id' => $model->id], ['class' => 'btn btn-primary']);
	        echo Html::aACL('Delete', [$urlPath . '/delete', 'id' => $model->id], [
	            'class' => 'btn btn-danger',
	            'data' => [
	                'confirm' => 'Are you sure you want to delete this item?',
	                'method' => 'post',
	            ],
	        ]);
			?>
			</div>
			<h1>&nbsp;</h1>
		</div>
		<?php
		echo $this->render("{$path}/view", ['model'=>$oneModel, 'onlyDetail' => true]); 
	}
?>


<?php
//
// Render the add modal
//---------------------------------------------------------------------------- ?>
<div class="crud-modal-add modal fade"><div class="modal-dialog modal-md"><div class="modal-content">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
	<h4 class="modal-title"><?= 'Add' ?></h4>
</div>
<div class="modal-body">
	<?php
		$relatedModelClass = StringHelper::dirname(get_class($model)) . "\\" . $refClass;
		$relatedModel = new $relatedModelClass(); 
		$relatedModel->loadDefaultValues();
		foreach ($refColumns as $refColumn => $thisColumn)
			$relatedModel->$refColumn = $model->$thisColumn;
	?>
	<?= $this->render("{$path}/_form", [
		'model' => $relatedModel,
		'appendGoBack' => true,
		'hiddenColumns' => $parentColumns,
		'formOptions' => [ 'labelColSize' => 2 ],
		'submitContainerClass' => 'col-xs-12 text-right',
	]); ?>
</div></div>
</div></div>

