<?php

use yii\helpers\Inflector;
use common\yii\helpers\StringHelper;
use yii\helpers\VarDumper;
use common\yii\helpers\SchemaHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>
use yii\helpers\Url;
use common\yii\helpers\Html;
use common\yii\helpers\ArrayHelper;
use common\yii\gii\generators\crud\event\CrudEvent;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>

<?= "<?php\n" ?>
//
// Header
//---------------------------------------------------------------------------- ?>
<div class="crud-page-header page-header">
	<div class="btn-group btn-group-sm" style="float:right;">
		<?= "<?php Yii::\$app->trigger(CrudEvent::RENDER_INDEX_BUTTONS, new CrudEvent(['modelClass' => '{$generator->modelClass}'])); ?>\n"; ?>
		<?= "<?= " ?>Html::aACL("<span class='fa fa-plus'></span> " . <?= $generator->generateString("Add") ?>, ['create'], ['class' => 'btn btn-success']) ?>
		<?php if(!empty($generator->searchModelClass)): echo "\n"; ?>
		<?= "<?= " ?>Html::aACL("<span class='fa fa-search'></span> " . <?= $generator->generateString("Search") ?>, '#', ['class' => 'btn btn-info', 'onclick' => "$('.crud-modal-search').modal('show');"]) ?>
		<?php endif; echo "\n"; ?>
	</div>
	<h1>
		<a href="<?= "<?= " ?>Url::toRoute(['index']); ?>"><?= "<?= " ?>Html::encode($this->title) ?></a>
	</h1>
</div>

<?php if(!empty($generator->searchModelClass)): ?>
<?= "<?php\n" ?>
//
// Search modal
//---------------------------------------------------------------------------- ?>
<div class="modal fade crud-modal-search">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title"><?= "<?= " ?><?= $generator->generateString("Advanced search") ?> ?></h4>
			</div>
			<div class="modal-body">
				<?= "<?php " ?>echo $this->render('_search', ['model' => $searchModel]); ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?= "<?php\n" ?>
//
// Render the grid
//---------------------------------------------------------------------------- ?>
<?= "<?php\n" ?>
	$columns = require(__DIR__ . "/_grid_columns.php");
	Yii::$app->trigger(CrudEvent::RENDER_INDEX_BEFORE_GRID, new CrudEvent([
		'modelClass' => '<?= $generator->modelClass ?>', 
		'dataProvider' => $dataProvider, 
		'columns' => $columns
	]));
	$columnsForGrid = ArrayHelper::merge($columns['active'], [	
		'__action' => ['class' => 'common\yii\grid\ActionColumn'],
	]);
	$event = new CrudEvent([
		'modelClass' => '<?= $generator->modelClass ?>', 
		'columns' => $columnsForGrid
	]);
	Yii::$app->trigger(CrudEvent::FILTER_GRID_COLUMNS, $event);
?>
<?= "<?= " ?>GridView::widget([
	'dataProvider' => $dataProvider,
	'options' => ['class' => 'grid-view crud-grid-view'],
	<?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n" : "\n"; ?>
	'columns' => $event->columns,
]); ?>
<?= "<?php Yii::\$app->trigger(CrudEvent::RENDER_INDEX_AFTER_GRID, new CrudEvent(['modelClass' => '{$generator->modelClass}', 'dataProvider' => \$dataProvider, 'columns' => \$columns])); ?>\n"; ?>


