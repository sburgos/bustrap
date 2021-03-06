<?php
use common\yii\helpers\Html;
use common\yii\gii\generators\crud\event\CrudEvent;

/* @var $this yii\web\View */
/* @var $model orm\admin\RoleAdminPermit */

$this->title = 'Role Admin Permit' . ': ' . $model->regex;
$this->params['breadcrumbs'][] = ['label' => 'Role Admin Permits', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Role Admin Permit' . ": " . $model->regex, 'url' => ['view', 'id' => $model->id]];
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