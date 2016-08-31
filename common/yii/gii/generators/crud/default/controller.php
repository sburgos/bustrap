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
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\helpers\VarDumper;
use yii\filters\VerbFilter;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, <?php 
        	echo count($fksNames) > 0 ? "[\n\t\t\t'" . implode("',\n\t\t\t'", $fksNames) . "'\n\t\t]" : "null";
        ?>);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }

    /**
     * Displays a single <?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
        return $this->render('view', [
            'model' => $this->findModel(<?= $actionParams ?>),
        ]);
    }
<?php foreach ($relations as $relationName => $relation) : 
		if (!$relation['hasMany'] || isset($relation['viaTable'])) 
		{
			if (!$relation['oneToOne'])
				continue; 
		}?>
	/**
	 * Tab for viewing <?= $relationName ?> for a single model
	 *
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
	 */
	public function actionView<?= $relationName ?>(<?= $actionParams ?>)
	{
<?php 
		$parentColumns = array_keys($relation['link']);
		$parentColumns[] = lcfirst(StringHelper::basename($class));		
		
		if ($relation['oneToOne']) : 
?>		
		$model = $this->findModel(<?= $actionParams ?>);
		return $this->render('view-relation-onetoone', [
            'model' => $model,
            'relationName' => '<?= $relationName ?>',
            'refClass' => '<?= $relation['refClass'] ?>',
            'parentColumns' => <?= (count($parentColumns) == 0 ? "[],\n" : "['". implode("', '", $parentColumns) . "'],\n") ?>
            'refColumns' => <?= VarDumper::export($relation['link']) ?>,
            'oneModel' => $model->get<?= $relationName ?>()->one(),
            'oneToOne' => <?= $relation['oneToOne'] ? 'true' : 'false' ?>,
        ]);
<?php else : 
?>
		$model = $this->findModel(<?= $actionParams ?>);
		return $this->render('view-relation', [
            'model' => $model,
            'relationName' => '<?= $relationName ?>',
            'refClass' => '<?= $relation['refClass'] ?>',
            'parentColumns' => <?= (count($parentColumns) == 0 ? "[],\n" : "['". implode("', '", $parentColumns) . "'],\n") ?>
            'refColumns' => <?= VarDumper::export($relation['link']) ?>,
            'dataProvider' => new \yii\data\ActiveDataProvider([
            	'query' => $model->get<?= $relationName ?>(),
            ]),
            'oneToOne' => <?= $relation['oneToOne'] ? 'true' : 'false' ?>,
        ]);
<?php endif; ?>
	}
	
<?php endforeach; ?>
    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new <?= $modelClass ?>();
        $model->loadDefaultValues(); 

        if (Yii::$app->request->isPost) 
    	{
    		try
    		{
    			$post = Yii::$app->request->post();
    			if ($model->load($post) && $model->save())
    			{
    				\Yii::$app->getSession()->setFlash('success', 'Se agregó correctamente');
    				if (isset($_GET['__goback']))
        				return $this->redirect($_GET['__goback']);
    				return $this->redirect(['view', <?= $urlParams ?>]);
    			}
    			else
    			{
    				\Yii::$app->getSession()->setFlash('warning', 'No se pudo agregar: ' . VarDumper::export($model->getErrors()));
    			}
    		}
	        catch (\Exception $ex) {
	        	\Yii::$app->getSession()->setFlash('error', $ex->getMessage());
	        }
	        if (isset($_GET['__goback']))
        		return $this->redirect($_GET['__goback']);
        }
    	
    	return $this->render('create', [
			'model' => $model,
		]);
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
    	$model = $this->findModel(<?= $actionParams ?>);
    	if (Yii::$app->request->isPost) 
    	{
    		try
    		{
    			$post = Yii::$app->request->post();
    			$scopedPost = $model->formName() === null || $model->formName() == '' ? $post : $post[$model->formName()];
    			if ($model->load($post) && $model->save()) 
    			{
    				\Yii::$app->getSession()->setFlash('success', 'Se guardo correctamente');
    				if (isset($_GET['__goback']))
        				return $this->redirect($_GET['__goback']);
    				return $this->redirect(['view', <?= $urlParams ?>]);
    			}
    			else
    			{
    				\Yii::$app->getSession()->setFlash('warning', 'No se pudo guardar: ' . VarDumper::export($model->getErrors()));
    			}
    		}
    		catch (\Exception $ex) {
	        	\Yii::$app->getSession()->setFlash('error', $ex->getMessage());
	        }
    		if (isset($_GET['__goback']))
        		return $this->redirect($_GET['__goback']);
        }

		return $this->render('update', [
			'model' => $model,
		]);
    }

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {
    	$model = $this->findModel(<?= $actionParams ?>);
    	try {
        	if ($model->delete())
        		\Yii::$app->getSession()->setFlash('success', 'Se elimino correctamente');
        	else
        		\Yii::$app->getSession()->setFlash('warning', 'No se pudo eliminar');
        }
        catch (\Exception $ex) {
        	\Yii::$app->getSession()->setFlash('error', $ex->getMessage());
        }

        if (isset($_GET['__goback']))
        	return $this->redirect($_GET['__goback']);
        return $this->redirect(['index']);
    }

    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParams ?>)
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Allows getting a single model based on its primary key value for
     * ajax requests for autocomplete fields for the AutoComplete widget
     * 
     * @return string
     */
    public function actionAjaxGet(<?= $actionParams ?>)
    {
    	if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) 
    	{
	    	return json_encode([
    			'value' => <?= $condition ?>, 
    			'label' => $model->obtainFKLabel(),
    			'data' => $model->toArray(),
    		]);
    	}
    	return json_encode(null);
    }
    
    /**
     * Allows getting a multiple model based on a search value for
     * ajax requests for autocomplete fields for the AutoComplete widget
     * 
     * @param string $term
     * @return string json encoded
     */
    public function actionAjaxList($term = null) 
    {
<?php 
reset($pks);
$idField = current($pks);
$labelFields = ($firstNameColumn === null) ? [$idField] : [$firstNameColumn];
$labelFieldsFormat = trim(str_pad("", count($labelFields), "%s "));
?>
		$labelFields = explode(",", <?= $modelClass ?>::$__AJAX_LABEL_FIELDS_COMMA_SEPARATED);
		$labelFormat = <?= $modelClass ?>::$__AJAX_LABEL_FORMAT;
		
    	$rows = [];
    	if ($term !== null)
    	{
    		$qry = <?= $modelClass ?>::find();
    		<?php foreach ($pks as $pk) : echo "\n"; ?>
    		$qry->orWhere('<?= $pk ?>=:<?= $pk ?>', [':<?= $pk ?>' => $term]);
    		<?php echo "\n"; endforeach; ?>
    		foreach ($labelFields as $field)
    			$qry->orWhere($field . ' LIKE :term', [':term' => "%{$term}%"]);
    		$allRows = $qry->limit(100)->all();
    		foreach ($allRows as $row) 
    		{
    			$rows[] = [
    				'value' => $row-><?= $idField ?>,
    				'label' => $row->obtainFKLabel($labelFields, $labelFormat),
    				'data' => $row->toArray(),
    			];
    		}
    	}
    	return json_encode($rows);
    }
}
