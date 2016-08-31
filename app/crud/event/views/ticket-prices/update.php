<?php
use common\yii\helpers\Html;
use common\yii\gii\generators\crud\event\CrudEvent;

/* @var $this yii\web\View */
/* @var $model orm\event\TicketPrices */

$this->title = 'Ticket Prices' . ': ' . $model->priceName;
$this->params['breadcrumbs'][] = ['label' => 'Ticket Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Ticket Prices' . ": " . $model->priceName, 'url' => ['view', 'id' => $model->id]];
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