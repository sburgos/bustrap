<?php
use yii\helpers\Url;
use common\yii\helpers\Html;
use common\yii\helpers\ArrayHelper;
use common\yii\gii\generators\crud\event\CrudEvent;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel orm\event\search\SearchAssistant */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Assistants';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
//
// Header
//---------------------------------------------------------------------------- ?>
<div class="crud-page-header page-header">
	<div class="btn-group btn-group-sm" style="float:right;">
		<?php Yii::$app->trigger(CrudEvent::RENDER_INDEX_BUTTONS, new CrudEvent(['modelClass' => 'orm\event\Assistant'])); ?>
		<?= Html::aACL("<span class='fa fa-plus'></span> " . 'Add', ['create'], ['class' => 'btn btn-success']) ?>
		
		<?= Html::aACL("<span class='fa fa-search'></span> " . 'Search', '#', ['class' => 'btn btn-info', 'onclick' => "$('.crud-modal-search').modal('show');"]) ?>
		
	</div>
	<h1>
		<a href="<?= Url::toRoute(['index']); ?>"><?= Html::encode($this->title) ?></a>
	</h1>
</div>

<?php
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
				<h4 class="modal-title"><?= 'Advanced search' ?></h4>
			</div>
			<div class="modal-body">
				<?php echo $this->render('_search', ['model' => $searchModel]); ?>
			</div>
		</div>
	</div>
</div>

<?php
//
// Render the grid
//---------------------------------------------------------------------------- ?>
<?php
	$columns = require(__DIR__ . "/_grid_columns.php");
	Yii::$app->trigger(CrudEvent::RENDER_INDEX_BEFORE_GRID, new CrudEvent([
		'modelClass' => 'orm\event\Assistant', 
		'dataProvider' => $dataProvider, 
		'columns' => $columns
	]));
	$columnsForGrid = ArrayHelper::merge($columns['active'], [	
		'__action' => ['class' => 'common\yii\grid\ActionColumn'],
	]);
	$event = new CrudEvent([
		'modelClass' => 'orm\event\Assistant', 
		'columns' => $columnsForGrid
	]);
	Yii::$app->trigger(CrudEvent::FILTER_GRID_COLUMNS, $event);
?>
<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'options' => ['class' => 'grid-view crud-grid-view'],
	'filterModel' => $searchModel,
	'columns' => $event->columns,
]); ?>
<?php Yii::$app->trigger(CrudEvent::RENDER_INDEX_AFTER_GRID, new CrudEvent(['modelClass' => 'orm\event\Assistant', 'dataProvider' => $dataProvider, 'columns' => $columns])); ?>


