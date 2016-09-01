<?php
use common\yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model orm\bus\Line */

$this->title = 'Create Line';
$this->params['breadcrumbs'][] = ['label' => 'Lines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="crud-page-header page-header">
	<h1><?= Html::encode($this->title) ?></h1>
</div>

<?= $this->render('_form', [
	'model' => $model,
]) ?>
