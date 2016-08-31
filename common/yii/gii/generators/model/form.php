<?php
use common\yii\helpers\StringHelper;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\form\Generator */

$dbConnections = [];
foreach (array_keys(\Yii::$app->components) as $componentId) {
	if (StringHelper::startsWith($componentId, 'db'))
		$dbConnections[$componentId] = $componentId;
}
$onClick = '$("#' . Html::getInputId($generator, 'module') . '").val($(this).html()).keyup()';
echo $form->field($generator, 'module', ['inputOptions' => [
	'class' => 'form-control',
	'autofocus' => 'autofocus',
	'onkeyup' => 'populateModule(this);'
]])->hint("<button type='button' class='btn btn-xs' onclick='{$onClick}'>event</button>
    <button type='button' class='btn btn-xs' onclick='{$onClick}'>admin</button> ", ["style" => "display:block;"]);
echo $form->field($generator, 'ns');
echo $form->field($generator, 'db');
echo $form->field($generator, 'tableName', ['inputOptions' => [
	'class' => 'form-control',
	'autofocus' => 'autofocus',
]]);
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'baseClass');
echo $form->field($generator, 'enableI18N')->checkbox();
echo $form->field($generator, 'messageCategory');

$keys = ['module','db','tableName','modelClass','ns','baseClass', 'messageCategory'];
$ids = [];
foreach ($keys as $key)
	$ids[$key] = Html::getInputId($generator, $key);
?>
<script>
function populateModule()
{
	var moduleName = $("#<?= $ids['module'] ?>").val();
	var moduleNameCap = moduleName.charAt(0).toUpperCase() + moduleName.slice(1);
	if (moduleName == 'event')
		$("#<?= $ids['db'] ?>").val("db");
    else if (moduleName == 'admin')
        $("#<?= $ids['db'] ?>").val("db");

    if(moduleName == 'event')
        $("#<?= $ids['tableName'] ?>").val("usmp.*");

    if(moduleName == 'admin')
        $("#<?= $ids['tableName'] ?>").val("usmp_admin.*");

	$("#<?= $ids['messageCategory'] ?>").val("orm/" + moduleName);
}
</script>