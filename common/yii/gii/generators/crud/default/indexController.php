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

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use common\yii\gii\generators\crud\event\CrudEvent;

/**
 * IndexController displays a home page for the CRUD
 */
class IndexController extends \yii\web\Controller
{
    /**
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
    	<?php 
    	$modelClass = $generator->modelClass; 
    	if (($pos = strpos($modelClass::tableName(), '.')) !== false) {
    		$schemaName = substr($modelClass::tableName(), 0, $pos);
    	} else {
    		$schemaName = '';
    	}
    	$allRelations = SchemaHelper::generateRelations($modelClass::getDb(), $schemaName, StringHelper::dirname($modelClass));
    	$allLinks = [];
    	foreach ($classes as $classFullName)
    	{
    		$className = StringHelper::basename($classFullName);
    		$viewName = Inflector::camel2id($className);
    		$allLinks[$viewName] = [
    			'label' => Inflector::pluralize(Inflector::camel2words($className)),
    			'url' => "{$viewName}/index",
    		];
    	}
    	echo "\$allLinks = [\n";
    	foreach ($allLinks as $linkId => $linkInfo)
    	{
    		echo "\t\t\t'{$linkId}' => ['label' => " . $generator->generateString($linkInfo['label']) . ", 'url' => ['{$linkInfo['url']}']],\n";
    	}
    	echo "\t\t];\n";
		?>
    	$event = new CrudEvent(['moduleId' => '<?= $generator->moduleID ?>', 'links' => $allLinks]);
    	Yii::$app->trigger(CrudEvent::FILTER_INDEX_PAGE_LINKS, $event); 
    	
        return $this->render('index', [
            'links' => $event->links,
            'moduleId' => '<?= $generator->moduleID ?>',
        ]);
    }
}
