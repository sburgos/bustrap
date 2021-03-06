<?php
use common\yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model orm\event\AdminManager */

$this->title = 'Create Admin Manager';
$this->params['breadcrumbs'][] = ['label' => 'Admin Managers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="crud-page-header page-header">
	<h1><?= Html::encode($this->title) ?></h1>
</div>

<?= $this->render('_form', [
	'model' => $model,
]) ?>
