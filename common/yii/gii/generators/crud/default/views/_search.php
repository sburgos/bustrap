<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use common\yii\helpers\SchemaHelper;
use common\yii\db\BaseActiveRecord;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
	
?>
use yii\helpers\Html;
use common\yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */

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
<?php foreach ($generator->getColumnNames() as $attribute)
{
	if (in_array($attribute, ['created_at', 'created_by', 'updated_at', 'updated_by']))
		continue;
	echo "\t<?= " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
} 
?>
	</div>

	<div class="row actionbar">
		<div class="col-xs-6 text-left">
			<?= "<?= " ?>Html::resetButton(<?= $generator->generateString('Reset') ?>, ['class' => 'btn btn-default']) ?>
		</div>
		<div class="col-xs-6 text-right">
			<?= "<?= " ?>Html::submitButton(<?= $generator->generateString('Search') ?>, ['class' => 'btn btn-primary']) ?>
		</div>
	</div>
	
<?= "<?php " ?>ActiveForm::end(); ?>
