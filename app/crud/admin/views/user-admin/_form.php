<?php
use yii\helpers\Html;
use common\yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model orm\admin\UserAdmin */
/* @var $form common\yii\widgets\ActiveForm */

// 
// Determine the form action
//------------------------------------------------------------------------------
if (!isset($formAction)) {
	if ($model->isNewRecord)
		$formAction = ['/admin/crud/user-admin/create'];
	else {
		$formAction = ['/admin/crud/user-admin/update'];
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
	<?php if (in_array('username', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'username')->textInput(['maxlength' => 50])->hint('Used for login') ?>
	<?php if (in_array('username', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('displayName', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'displayName')->textInput(['maxlength' => 50])->hint('Name to display') ?>
	<?php if (in_array('displayName', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('secretToken', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'secretToken')->tokenInput(['maxlength' => 32])->hint('Random generated string to protect the account. This is used internally.') ?>
	<?php if (in_array('secretToken', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('authToken', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'authToken')->tokenInput(['maxlength' => 32])->hint('Token for authentication for Yii. This is what is used to create the cookies and other Yii stuff.') ?>
	<?php if (in_array('authToken', $hiddenColumns)) echo "</div>"; ?>

	<?php if ($model->isNewRecord) : ?>
	<?php if (in_array('userPassword', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'userPassword')->passwordInput(['maxlength' => 255])->hint('The password hashed with bCrypt.') ?>
	<?php if (in_array('userPassword', $hiddenColumns)) echo "</div>"; ?>
	<?php endif; ?>

	<?php if (in_array('active', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'active')->checkbox(null, false)->hint('If the account is active for login.') ?>
	<?php if (in_array('active', $hiddenColumns)) echo "</div>"; ?>

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
