<?php
use yii\helpers\Html;
use common\yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model orm\bus\Route */
/* @var $form common\yii\widgets\ActiveForm */

// 
// Determine the form action
//------------------------------------------------------------------------------
if (!isset($formAction)) {
	if ($model->isNewRecord)
		$formAction = ['/bus/crud/route/create'];
	else {
		$formAction = ['/bus/crud/route/update'];
		if (count($model->primaryKey()) == 1)
		{
			$modelPk = $model->primaryKey();
			$pkKey = current($modelPk);
			$formAction['id'] = $model->{$pkKey};
		}
		else { 
			foreach ($model->primaryKey() as $key)
				$formAction[$key] = $model->{$key};
		}
	}
}

// 
// Determine if __goback should be appended to the form action
//------------------------------------------------------------------------------
if (isset($appendGoBack)) {
	if ($appendGoBack === true)
		$formAction['__goback'] = $_SERVER['REQUEST_URI'];
	else if (is_string($appendGoBack))
		$formAction['__goback'] = appendGoBack;
} else if (isset($_GET['__goback'])) {
	$formAction['__goback'] = $_GET['__goback'];
}

// 
// Determine columns to hide
//------------------------------------------------------------------------------
if (!isset($hiddenColumns) || !is_array($hiddenColumns))
	$hiddenColumns = [];

// 
// Determine form options
//------------------------------------------------------------------------------
if (!isset($formOptions) || !is_array($formOptions))
	$formOptions = [];
	
// 
// Determine submit container class
//------------------------------------------------------------------------------
if (!isset($submitContainerClass))
	$submitContainerClass = "col-sm-10 col-sm-push-2";

// 
// Setup submitButton settings
//------------------------------------------------------------------------------
$submitButton = [
	'label' => $model->isNewRecord ? 'Add' : 'Save',
	'options' => [
		'class' => 'btn ' . ($model->isNewRecord ? "btn-success" : "btn-primary"),
	],
];
if ($formAction == ['index']) {
	$submitButton['label'] = 'Search';
	$submitButton['options']['class'] = "btn btn-info";
}


// 
// Render the form
//------------------------------------------------------------------------------
$form = ActiveForm::begin(array_merge([
	'action' => $formAction,
	'options' => ['class' => 'crud-form'],
], $formOptions)); 
?>

	<div class="row fields">
	<?php if (in_array('lineId', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'lineId')->widget(\common\yii\jui\AutoComplete::className(), [
			'crudUrl' => \yii\helpers\Url::toRoute(['/bus/crud/line']),
		]) ?>
	<?php if (in_array('lineId', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('latitude', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'latitude')->textInput(['maxlength' => 45]) ?>
	<?php if (in_array('latitude', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('longitude', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'longitude')->textInput(['maxlength' => 45]) ?>
	<?php if (in_array('longitude', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('ida', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'ida')->checkbox(null, false) ?>
	<?php if (in_array('ida', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('name', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'name')->textInput(['maxlength' => 200]) ?>
	<?php if (in_array('name', $hiddenColumns)) echo "</div>"; ?>

	</div>

	<div class="row actionbar">
		<div class="<?= $submitContainerClass; ?>">
			<?php if (isset($formAction['__goback'])) : ?>
				<?= Html::a('Cancel', 
					$formAction['__goback'], [
						'class' => 'btn btn-default', 
						'tabindex' => -1,
						'onclick' => "if ($(this).parents('.modal').length == 0) return true; $(this).parents('.modal').modal('hide');return false;",
					]
				) ?>
			<?php endif; ?>
			<?= Html::submitButton($submitButton['label'], $submitButton['options']) ?>
		</div>
	</div>
	
<?php ActiveForm::end(); ?>
