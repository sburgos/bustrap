<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\crud\Generator */

$onClick = '$("#' . Html::getInputId($generator, 'moduleID') . '").val($(this).html()).change()';
echo $form->field($generator, 'moduleID', ['inputOptions' => [
	'class' => 'form-control',
	'autofocus' => 'autofocus',
	'onchange' => 'autoPopulateModel(this);'
]])->hint("<button type='button' class='btn btn-xs' onclick='{$onClick}'>bus</button>
    <button type='button' class='btn btn-xs' onclick='{$onClick}'>superadmin</button> ", ["style" => "display:block;"]);
echo $form->field($generator, 'modelClass', ['inputOptions' => [
	'class' => 'form-control',
	'onchange' => 'autoPopulate(this);'
]]);
echo $form->field($generator, 'allClassesInNamespace')->checkbox([
	'onclick' => 'autoMark(this);',
]);
echo $form->field($generator, 'searchModelClass');
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'baseControllerClass');
echo $form->field($generator, 'indexWidgetType')->dropDownList([
    'grid' => 'GridView',
]);
echo $form->field($generator, 'enableI18N')->checkbox();
echo $form->field($generator, 'messageCategory');

?>
<script>
function autoPopulateModel(modelInput)
{
	var moduleName = $(modelInput).val();
	var defClass = "?";
	if (moduleName == "bus")
		defClass = "Bus";
    else if (moduleName == "superadmin")
        defClass = "Admin";
	$("#<?= Html::getInputId($generator, 'modelClass') ?>").val("orm\\" + moduleName + "\\" + defClass);
	if (defClass != "?")
		autoPopulate("#<?= Html::getInputId($generator, 'modelClass') ?>");
}
function autoPopulate(modelInput)
{
	var modelClass = $(modelInput).val();
	var lastSlash = modelClass.lastIndexOf("\\");
	var parts = modelClass.split("\\");
	var modelName = parts[parts.length - 1];
	var moduleName = $("#<?= Html::getInputId($generator, 'moduleID') ?>").val();
	
	var searchName = modelClass.substring(0, lastSlash + 1) + "search\\Search" + modelName;
	var controllerClass = "crud\\" + moduleName + "\\controllers\\" + modelName + "Controller";
	$("#<?= Html::getInputId($generator, 'searchModelClass') ?>").val(searchName);
	$("#<?= Html::getInputId($generator, 'controllerClass') ?>").val(controllerClass);
	//$("#<?= Html::getInputId($generator, 'moduleID') ?>").val(moduleName.toLowerCase() + "/crud").css('display', 'block'); 
	$(".field-<?= Html::getInputId($generator, 'moduleID') ?> .sticky-value").hide();
	$("#<?= Html::getInputId($generator, 'messageCategory') ?>").val("crud/" + moduleName.toLowerCase()).css('display', 'block'); 
	$(".field-<?= Html::getInputId($generator, 'messageCategory') ?> .sticky-value").hide();
}
function autoMark(checkbox)
{
	if ($(checkbox).prop('checked'))
	{
		$(".field-<?= Html::getInputId($generator, 'searchModelClass') ?>").hide();
		$(".field-<?= Html::getInputId($generator, 'controllerClass') ?>").hide();
		$(".field-<?= Html::getInputId($generator, 'searchModelClass') ?>").hide();
	}
	else
	{
		$(".field-<?= Html::getInputId($generator, 'searchModelClass') ?>").show();
		$(".field-<?= Html::getInputId($generator, 'controllerClass') ?>").show();
		$(".field-<?= Html::getInputId($generator, 'searchModelClass') ?>").show();
	}
}
</script>