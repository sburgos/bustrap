<?php
use yii\helpers\Html;
use common\yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model orm\admin\search\SearchRestClientRole */

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
	<?= $form->field($model, 'restClientId') ?>

	<?= $form->field($model, 'roleRestId') ?>

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
