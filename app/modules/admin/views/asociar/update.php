<?php
use common\yii\helpers\Html;
use common\yii\gii\generators\crud\event\CrudEvent;

/* @var $this yii\web\View */
/* @var $model orm\event\AdminManager */

$this->title = 'Admin Manager' . ': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Admin Managers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Admin Manager' . ": " . $model->id, 'url' => ['view', 'id' => $model->id]];
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