<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;
use common\yii\helpers\SchemaHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
if (empty($nameAttribute)) {
	$nameAttribute = current($generator->getTableSchema()->primaryKey);
}
echo "<?php\n";
?>

use yii\helpers\Url;
use common\yii\helpers\Html;
use yii\widgets\DetailView;
use common\yii\gii\generators\crud\event\CrudEvent;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

if (!isset($onlyDetail)) {
	$this->title = <?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?> . ": " . $model-><?= $nameAttribute ?>;
	$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
}
?>

<?= "<?php\n" .
"//\n// Render title\n".
"//----------------------------------------------------------------------------\n".
"?>\n" ?>
<?= "<?php " ?> if (!isset($onlyDetail)) : ?>
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
<?= "<?php " ?> endif; ?>

<?= "<?php\n" .
"//\n// Render child table tabs\n".
"//----------------------------------------------------------------------------\n".
"?>\n" ?>
<?= "<?php " ?> if (!isset($onlyDetail)) : ?>
<?= "<?= " ?>$this->render('_tabs', [
	'model' => $model,
]) ?>
<?= "<?php " ?> endif; ?>

<?= "<?php\n" .
"//\n// Render details\n".
"//----------------------------------------------------------------------------\n".
"?>\n" ?>
<?= "<?= " ?>DetailView::widget([
	'model' => $model,
	'template' => '<div class="form-group"><label class="col-sm-2 control-label">{label}:</label><div class="col-sm-10 form-control-static">{value}</div></div>',
	'options' => [
		'tag' => 'div',
		'class' => 'form-horizontal crud-view-detail',
	],
	'attributes' => [
<?php
	if (($tableSchema = $generator->getTableSchema()) === false) {
	    foreach ($generator->getColumnNames() as $name) {
	        echo "\t\t'" . $name . "',\n";
	    }
	} else {
	    foreach ($generator->getTableSchema()->columns as $column) {
	    	if (SchemaHelper::isPasswordColumn($column))
	    		continue;
	        $format = $generator->generateColumnFormat($column);
	        echo "\t\t'" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
	    }
	}
?>
	],
]) ?>


