<?php

namespace crud\bus\controllers;

use Yii;
use orm\bus\Line;
use orm\bus\search\SearchLine;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\VarDumper;
use yii\filters\VerbFilter;

/**
 * LineController implements the CRUD actions for Line model.
 */
class LineController extends Controller
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
     * Lists all Line models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchLine();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, null);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Line model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
	/**
	 * Tab for viewing Buses for a single model
	 *
     * @param integer $id
     * @return mixed
	 */
	public function actionViewBuses($id)
	{
		$model = $this->findModel($id);
		return $this->render('view-relation', [
            'model' => $model,
            'relationName' => 'Buses',
            'refClass' => 'Bus',
            'parentColumns' => ['idLine', 'line'],
            'refColumns' => [
    'idLine' => 'id',
],
            'dataProvider' => new \yii\data\ActiveDataProvider([
            	'query' => $model->getBuses(),
            ]),
            'oneToOne' => false,
        ]);
	}
	
	/**
	 * Tab for viewing Routes for a single model
	 *
     * @param integer $id
     * @return mixed
	 */
	public function actionViewRoutes($id)
	{
		$model = $this->findModel($id);
		return $this->render('view-relation', [
            'model' => $model,
            'relationName' => 'Routes',
            'refClass' => 'Route',
            'parentColumns' => ['lineId', 'line'],
            'refColumns' => [
    'lineId' => 'id',
],
            'dataProvider' => new \yii\data\ActiveDataProvider([
            	'query' => $model->getRoutes(),
            ]),
            'oneToOne' => false,
        ]);
	}
	
    /**
     * Creates a new Line model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Line();
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
    				return $this->redirect(['view', 'id' => $model->id]);
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
     * Updates an existing Line model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
    	$model = $this->findModel($id);
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
    				return $this->redirect(['view', 'id' => $model->id]);
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
     * Deletes an existing Line model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	$model = $this->findModel($id);
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
     * Finds the Line model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Line the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Line::findOne($id)) !== null) {
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
    public function actionAjaxGet($id)
    {
    	if (($model = Line::findOne($id)) !== null) 
    	{
	    	return json_encode([
    			'value' => $id, 
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
		$labelFields = explode(",", Line::$__AJAX_LABEL_FIELDS_COMMA_SEPARATED);
		$labelFormat = Line::$__AJAX_LABEL_FORMAT;
		
    	$rows = [];
    	if ($term !== null)
    	{
    		$qry = Line::find();
    		
    		$qry->orWhere('id=:id', [':id' => $term]);
    		
    		foreach ($labelFields as $field)
    			$qry->orWhere($field . ' LIKE :term', [':term' => "%{$term}%"]);
    		$allRows = $qry->limit(100)->all();
    		foreach ($allRows as $row) 
    		{
    			$rows[] = [
    				'value' => $row->id,
    				'label' => $row->obtainFKLabel($labelFields, $labelFormat),
    				'data' => $row->toArray(),
    			];
    		}
    	}
    	return json_encode($rows);
    }
}
