<?php
use yii\helpers\VarDumper;
use yii\helpers\Inflector;
use common\yii\helpers\StringHelper;
use common\yii\helpers\SchemaHelper;
use yii\db\Schema;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

?>
<?= "<?php\n\$allColumns = [\n" ?>
<?php 
	$tableSchema = $generator->getTableSchema();
	$modelRelations = SchemaHelper::getModelFKRelations($generator->modelClass);
	foreach ($tableSchema->columns as $column) 
	{
		$options = [
			'class' => "common\yii\grid\DataColumn",
			'attribute' => $column->name,
			'format' => $generator->generateColumnFormat($column),
			'isPrimaryKey' => $column->isPrimaryKey,
			'contentOptions' => [
				'style' => '',
			],
			'headerOptions' => [
				'style' => '',
			],
		];
		if ($column->isPrimaryKey)
		{
			$options['isPrimaryKey'] = true;
			$options['controller'] = Inflector::camel2id(StringHelper::basename($generator->controllerID));
		}
		if (in_array($column->phpType, ['integer','number','boolean']) || count($column->enumValues))
		{
			$options['headerOptions']['style'] .= 'width:10px;text-align:center;';
			$options['contentOptions']['style'] .= 'width:10px;text-align:center;';
		}
		if (SchemaHelper::isImageColumn($column))
		{
			$options['format'] = ['s3Image', [ 'gridMode' => true ]];
			$options['headerOptions']['style'] .= 'text-align:center;';
			$options['contentOptions']['style'] .= 'width:80px;padding:0px;text-align:center;';
		}
		if (SchemaHelper::isFileColumn($column))
		{
			$options['format'] = ['s3File', [ 'gridMode' => true ]];
			$options['headerOptions']['style'] .= 'text-align:center;';
			$options['contentOptions']['style'] .= 'width:80px;padding:0px;text-align:center;';
		}
		if (SchemaHelper::isVideoColumn($column))
		{
			$options['format'] = ['video', [ 'gridMode' => true ]];
			$options['headerOptions']['style'] .= 'width:150px;text-align:center;';
			$options['contentOptions']['style'] .= 'width:150px;padding:0px;text-align:center;';
		}
		
		echo "'{$column->name}' => " . VarDumper::export($options) . ",\n";
	} 
	
	foreach ($modelRelations as $relationName => $relationInfo)
	{
		$options = [
			'class' => "yii\grid\DataColumn",
			'attribute' => "{$relationName}.{$relationInfo['displayColumn']}",
			'format' => 'text',
			'contentOptions' => [
				'style' => 'text-align:left;',
			],
			'header' => Inflector::camel2words($relationName),
			'headerOptions' => [
				'style' => 'text-align:center;',
			],
		];
	
		echo "'{$relationName}' => " . VarDumper::export($options) . ",\n";
	}
?>
];

return [
	'all' => $allColumns,
	'active' => [
<?php 
$count = 0;
$tableSchema = $generator->getTableSchema();
$modelRelations = SchemaHelper::getModelFKRelations($generator->modelClass);
foreach ($tableSchema->columns as $column)
{
	// Determine if it should be commented
	$commented = $count > 10;
	if (SchemaHelper::isPasswordColumn($column) || SchemaHelper::isTokenColumn($column))
		$commented = true;
	if (in_array($column->name, ['created_by', 'created_at', 'updated_by', 'updated_at']))
		$commented = true;
	if ($column->dbType == Schema::TYPE_TEXT && !SchemaHelper::isImageColumn($column))
		$commented = true;

	$str = "\t\t'{$column->name}' => \$allColumns['{$column->name}'],\n";
	if (StringHelper::endsWith($column->name, "Id"))
	{
		$colNameWithoutId = substr($column->name, 0, -2);
		if (array_key_exists($colNameWithoutId, $modelRelations))
			$str = "\t\t'{$column->name}' => \$allColumns['{$colNameWithoutId}'],\n//{$str}";
		if ($column->isPrimaryKey)
			$str = "\t\t'{$column->name}_PK' => \$allColumns['{$column->name}'],\n" . $str;
	}

	// Render commented or uncommented
	if ($commented)
		echo "// " . $str;
	else {
		echo $str;
		$count++;
	}
}
?>	
	],
];
