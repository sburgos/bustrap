<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use common\yii\helpers\SchemaHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>
use yii\helpers\Html;
use common\yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form common\yii\widgets\ActiveForm */

// 
// Determine the form action
//------------------------------------------------------------------------------
if (!isset($formAction)) {
	if ($model->isNewRecord)
		$formAction = ['/<?= $generator->moduleID . "/crud/" . Inflector::camel2id($generator->controllerID) ?>/create'];
	else {
		$formAction = ['/<?= $generator->moduleID . "/crud/" . Inflector::camel2id($generator->controllerID) ?>/update'];
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
	'label' => $model->isNewRecord ? <?= $generator->generateString('Add') ?> : <?= $generator->generateString('Save') ?>,
	'options' => [
		'class' => 'btn ' . ($model->isNewRecord ? "btn-success" : "btn-primary"),
	],
];
if ($formAction == ['index']) {
	$submitButton['label'] = <?= $generator->generateString('Search') ?>;
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
<?php foreach ($generator->tableSchema->columns as $column) 
{
	if ($column->name == 'created_at' || $column->name == 'updated_at') continue;
	if ($column->name == 'created_by' || $column->name == 'updated_by') continue;
	if ($column->autoIncrement) continue;
	if (SchemaHelper::isDateServerColumn($column) || 
		SchemaHelper::isDateTimeServerColumn($column) ||
		SchemaHelper::isSlugColumn($column))
		continue;
    if (in_array($column->name, $safeAttributes)) 
    {
    	$field = "\t<?= " . $generator->generateActiveField($column->name) . " ?>\n";
    	
    	// Wrap inside a hidden field if set
    	$field = "\t<?php if (in_array('{$column->name}', \$hiddenColumns)) echo \"<div style='display:none;'>\"; ?>\n\t{$field}";
    	$field .= "\t<?php if (in_array('{$column->name}', \$hiddenColumns)) echo \"</div>\"; ?>\n";
    	
    	if (SchemaHelper::isPasswordColumn($column))
    	{
    		echo "\t<?php if (\$model->isNewRecord) : ?>\n";
    		echo $field;
    		echo "\t<?php endif; ?>\n\n";
    	}
    	else {
    		echo $field . "\n";
    	}
    }
} 
?>
	</div>

	<div class="row actionbar">
		<div class="<?= "<?= \$submitContainerClass; ?>"; ?>">
			<?= "<?php if (isset(\$formAction['__goback'])) : ?>\n" ?>
				<?= "<?= " ?>Html::a(<?= $generator->generateString('Cancel') ?>, 
					$formAction['__goback'], [
						'class' => 'btn btn-default', 
						'tabindex' => -1,
						'onclick' => "if ($(this).parents('.modal').length == 0) return true; $(this).parents('.modal').modal('hide');return false;",
					]
				) ?>
			<?= "<?php endif; ?>\n" ?>
			<?= "<?= " ?>Html::submitButton($submitButton['label'], $submitButton['options']) ?>
		</div>
	</div>
	
<?= "<?php " ?>ActiveForm::end(); ?>
