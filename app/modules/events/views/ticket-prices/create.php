<?php
use common\yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model orm\event\TicketPrices */

$this->title = 'Create Ticket Prices';
$this->params['breadcrumbs'][] = ['label' => 'Ticket Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="crud-page-header page-header">
	<h1><?= Html::encode($this->title) ?></h1>
</div>

<?= $this->render('_form', [
	'model' => $model,
]) ?>
