<?php
use common\yii\helpers\Html;
use common\yii\gii\generators\crud\event\CrudEvent;

/* @var $this yii\web\View */
/* @var $model orm\admin\RestClientRole */

$this->title = 'Rest Client Role' . ': ' . $model->restClientId;
$this->params['breadcrumbs'][] = ['label' => 'Rest Client Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Rest Client Role' . ": " . $model->restClientId, 'url' => ['view', 'restClientId' => $model->restClientId, 'roleRestId' => $model->roleRestId]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="crud-page-header page-header">
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
// Render the form
//----------------------------------------------------------------------------
?>
<?= $this->render('_form', [
	'model' => $model,
]) ?>