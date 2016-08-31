<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use common\yii\helpers\StringHelper;
use common\yii\helpers\SchemaHelper;
use yii\helpers\Inflector;
use common\yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();
$fksNames = [];
foreach ($relations as $relationName => $relation)
{
	if ($relation['hasMany']) continue;
	$fksNames[] = lcfirst($relationName);
}

$firstNameColumn = SchemaHelper::getFirstStringColumnName($class::getTableSchema());
echo "<?php\n";
?>
namespace <?= StringHelper::dirname(StringHelper::dirname(ltrim($generator->controllerClass, '\\'))) ?>;

use Yii;

class Module extends \app\base\Module
{
}
