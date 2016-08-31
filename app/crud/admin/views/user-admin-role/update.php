<?php
use common\yii\helpers\Html;
use common\yii\gii\generators\crud\event\CrudEvent;

/* @var $this yii\web\View */
/* @var $model orm\admin\UserAdminRole */

$this->title = 'User Admin Role' . ': ' . $model->userAdminId;
$this->params['breadcrumbs'][] = ['label' => 'User Admin Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'User Admin Role' . ": " . $model->userAdminId, 'url' => ['view', 'userAdminId' => $model->userAdminId, 'roleAdminId' => $model->roleAdminId]];
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