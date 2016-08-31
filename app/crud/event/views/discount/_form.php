<?php
use yii\helpers\Html;
use common\yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model orm\event\Discount */
/* @var $form common\yii\widgets\ActiveForm */

// 
// Determine the form action
//------------------------------------------------------------------------------
if (!isset($formAction)) {
	if ($model->isNewRecord)
		$formAction = ['/event/crud/discount/create'];
	else {
		$formAction = ['/event/crud/discount/update'];
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
	<?php if (in_array('code', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'code')->textInput(['maxlength' => 45]) ?>
	<?php if (in_array('code', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('discount', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'discount')->textInput()->hint('from 0 to 100 (%)') ?>
	<?php if (in_array('discount', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('toDate', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'toDate')->dateTimeInput() ?>
	<?php if (in_array('toDate', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('fromDate', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'fromDate')->dateTimeInput() ?>
	<?php if (in_array('fromDate', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('eventId', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'eventId')->widget(\common\yii\jui\AutoComplete::className(), [
			'crudUrl' => \yii\helpers\Url::toRoute(['/event/crud/event']),
		]) ?>
	<?php if (in_array('eventId', $hiddenColumns)) echo "</div>"; ?>

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
