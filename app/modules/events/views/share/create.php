<?php
use common\yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model orm\event\Share */

$this->title = 'Create Share';
$this->params['breadcrumbs'][] = ['label' => 'Shares', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="crud-page-header page-header">
	<h1><?= Html::encode($this->title) ?></h1>
</div>

<?= $this->render('_form', [
	'model' => $model,
]) ?>
