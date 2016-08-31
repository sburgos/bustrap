<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
if (empty($nameAttribute)) {
	$nameAttribute = current($generator->getTableSchema()->primaryKey);
}
echo "<?php\n";
?>
use common\yii\helpers\Html;
use common\yii\gii\generators\crud\event\CrudEvent;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?> . ': ' . $model-><?= $nameAttribute ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?> . ": " . $model-><?= $nameAttribute ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = <?= $generator->generateString('Update') ?>;
?>

<div class="crud-page-header page-header">
	<h1><?= "<?= " ?>Html::encode($this->title) ?></h1>
</div>

<?= "<?php\n" .
"//\n// Render child table tabs\n".
"//----------------------------------------------------------------------------\n".
"?>\n" ?>
<?= "<?= " ?>$this->render('_tabs', [
	'model' => $model,
]) ?>

<?= "<?php\n" .
"//\n// Render the form\n".
"//----------------------------------------------------------------------------\n".
"?>\n" ?>
<?= "<?= " ?>$this->render('_form', [
	'model' => $model,
]) ?>