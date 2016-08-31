<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;
use common\yii\helpers\SchemaHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Url;
use common\yii\helpers\Html;
use common\yii\helpers\StringHelper;
use common\yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\yii\gii\generators\crud\event\CrudEvent;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?> . ": " . $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= "<?php\n" .
"//\n// Render title\n".
"//----------------------------------------------------------------------------\n".
"?>\n" ?>
<div class="crud-page-header page-header">
	<div class="btn-group btn-group-sm" style="float:right;">
		<?= "<?php Yii::\$app->trigger(CrudEvent::RENDER_VIEW_BUTTONS, new CrudEvent(['model' => \$model])); ?>\n"; ?>
		<?= "<?= " ?>Html::aACL(<?= $generator->generateString('Edit') ?>, ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary']) ?>
        <?= "<?= " ?>Html::aACL(<?= $generator->generateString('Delete') ?>, ['delete', <?= $urlParams ?>], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => <?= $generator->generateString('Are you sure you want to delete this item?') ?>,
                'method' => 'post',
            ],
        ]) ?>
	</div>
	<h1><?= "<?= " ?>Html::encode($this->title) ?></h1>
</div>

<?= "<?php\n" .
"//\n// Render child table tabs\n".
"//----------------------------------------------------------------------------\n".
"?>\n" ?>
<?= "<?= " ?>$this->render('_tabs', [
	'model' => $model,
]) ?>

<?= "<?php\n" ?>
//
// Render the grid
//---------------------------------------------------------------------------- ?>
<?= "<?php\n" ?>
	$path = "../" . <?= " Inflector::camel2id(\$refClass) " ?>;
	$columns = require(__DIR__ . "/{$path}/_grid_columns.php"); 

	//
	// Render before grid
	//--------------------------------------------------------------------------
	Yii::$app->trigger(CrudEvent::RENDER_VIEW_RELATION_BEFORE, new CrudEvent([
		'model' => $model, 
		'relatedClass' => $refClass
	]));
	
	//
	// Append action and spacing columns
	//--------------------------------------------------------------------------
	$columnsForGrid = ArrayHelper::merge(
		ArrayHelper::removeKeys($columns['active'], $parentColumns), 
		[
			'__spacing' => ['class' => 'yii\grid\Column' ],
			'__action' => [
				'class' => 'common\yii\grid\ActionColumn',
				'controller' => Inflector::camel2id($refClass),
				'deleteUrlParams' => [
					'__goback' => $_SERVER['REQUEST_URI'],
				],
				'editUrlParams' => [
					'__goback' => $_SERVER['REQUEST_URI'],
				],
				'headerOptions' => [
					'style' => 'padding:0px;',
				],
				'header' => Html::button(<?= $generator->generateString("Add"); ?>, [
					'class' => 'btn btn-success btn-block',
					'onclick' => "\$('.crud-modal-add').modal();",
				])
			],
		]
	);

	//
	// Trigger the filter columns before rendering
	//--------------------------------------------------------------------------
	$event = new CrudEvent([
		'modelClass' => StringHelper::dirname(get_class($model)) . "\\" . $refClass, 
		'columns' => $columnsForGrid,
		'relatedClass' => get_class($model),
	]);
	Yii::$app->trigger(CrudEvent::FILTER_GRID_COLUMNS, $event);

	//
	// Render the grid
	//--------------------------------------------------------------------------
	echo GridView::widget([
		'dataProvider' => $dataProvider,
		'options' => ['class' => 'grid-view crud-grid-view'],
		'columns' => $event->columns,
	]);
	
	//
	// Trigger rendering after the the grid view
	//--------------------------------------------------------------------------
	Yii::$app->trigger(CrudEvent::RENDER_VIEW_RELATION_AFTER, new CrudEvent([
		'model' => $model, 
		'relatedClass' => $refClass
	]));
?>


<?= "<?php\n" ?>
//
// Render the add modal
//---------------------------------------------------------------------------- ?>
<div class="crud-modal-add modal fade"><div class="modal-dialog modal-md"><div class="modal-content">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
	<h4 class="modal-title"><?= "<?= " . $generator->generateString("Add"); ?> ?></h4>
</div>
<div class="modal-body">
	<?= "<?php\n"; ?>
		$relatedModelClass = StringHelper::dirname(get_class($model)) . "\\" . $refClass;
		$relatedModel = new $relatedModelClass(); 
		$relatedModel->loadDefaultValues();
		foreach ($refColumns as $refColumn => $thisColumn)
			$relatedModel->$refColumn = $model->$thisColumn;
	?>
	<?= "<?= " ?>$this->render("{$path}/_form", [
		'model' => $relatedModel,
		'appendGoBack' => true,
		'hiddenColumns' => $parentColumns,
		'formOptions' => [ 'labelColSize' => 2 ],
		'submitContainerClass' => 'col-xs-12 text-right',
	]); ?>
</div></div>
</div></div>

