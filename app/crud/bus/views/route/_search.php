<?php
use yii\helpers\Html;
use common\yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model orm\bus\search\SearchRoute */

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

	<?= $form->field($model, 'lineId')->widget(\common\yii\jui\AutoComplete::className(), [
			'crudUrl' => \yii\helpers\Url::toRoute(['/bus/crud/line']),
		]) ?>

	<?= $form->field($model, 'latitude') ?>

	<?= $form->field($model, 'longitude') ?>

	<?= $form->field($model, 'ida')->checkbox(null, true, true) ?>

	<?= $form->field($model, 'name') ?>

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
