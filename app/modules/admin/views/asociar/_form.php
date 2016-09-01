<?php
use yii\helpers\Html;
use common\yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model orm\event\AdminManager */
/* @var $form common\yii\widgets\ActiveForm */

// 
// Determine the form action
//------------------------------------------------------------------------------
if (!isset($formAction)) {
	if ($model->isNewRecord)
		$formAction = ['/event/crud/admin-manager/create'];
	else {
		$formAction = ['/event/crud/admin-manager/update'];
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
	<?php if (in_array('manageId', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'manageId')->widget(\common\yii\jui\AutoComplete::className(), [
			'crudUrl' => \yii\helpers\Url::toRoute(['/event/crud/manager']),
		]) ?>
	<?php if (in_array('manageId', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('adminId', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
	<?= $form->field($model, 'adminId')->widget(\common\yii\jui\AutoComplete::className(), [
		'crudUrl' => \yii\helpers\Url::toRoute(['/admin/crud/user-admin']),
	]) ?>
	<?php if (in_array('adminId', $hiddenColumns)) echo "</div>"; ?>


<?php if(false): ?>
	<?php if (in_array('adminId', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'adminId')->textInput() ?>
	<?php if (in_array('adminId', $hiddenColumns)) echo "</div>"; ?>
<?php endif; ?>



	<?php if (in_array('status', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'status')->checkbox(null, false) ?>
	<?php if (in_array('status', $hiddenColumns)) echo "</div>"; ?>

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