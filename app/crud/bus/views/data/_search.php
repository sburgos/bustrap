<?php
use yii\helpers\Html;
use common\yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model orm\bus\search\SearchData */

// 
// Render the form
//------------------------------------------------------------------------------
$form = ActiveForm::begin([
	'action' => ['index'],
	'labelColSize' => 12,
	'options' => ['class' => 'crud-form crud-form-search'],
	'method' => 'get',
]);
?>

	<div class="row fields">
	<?= $form->field($model, 'id') ?>

	<?= $form->field($model, 'idSmartNode')->widget(\common\yii\jui\AutoComplete::className(), [
			'crudUrl' => \yii\helpers\Url::toRoute(['/bus/crud/smart-node']),
		]) ?>

	<?= $form->field($model, 'moveDate') ?>

	<?= $form->field($model, 'velocity') ?>

	<?= $form->field($model, 'isTraffic')->checkbox(null, true, true) ?>

	</div>

	<div class="row actionbar">
		<div class="col-xs-6 text-left">
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
		</div>
		<div class="col-xs-6 text-right">
			<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
		</div>
	</div>
	
<?php ActiveForm::end(); ?>
