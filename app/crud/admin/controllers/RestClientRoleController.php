<?php

namespace crud\admin\controllers;

use Yii;
use orm\admin\RestClientRole;
use orm\admin\search\SearchRestClientRole;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\VarDumper;
use yii\filters\VerbFilter;

/**
 * RestClientRoleController implements the CRUD actions for RestClientRole model.
 */
class RestClientRoleController extends Controller
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
     * Lists all RestClientRole models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchRestClientRole();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, null);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RestClientRole model.
     * @param string $restClientId
     * @param integer $roleRestId
     * @return mixed
     */
    public function actionView($restClientId, $roleRestId)
    {
        return $this->render('view', [
            'model' => $this->findModel($restClientId, $roleRestId),
        ]);
    }
    /**
     * Creates a new RestClientRole model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RestClientRole();
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
    				return $this->redirect(['view', 'restClientId' => $model->restClientId, 'roleRestId' => $model->roleRestId]);
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
     * Updates an existing RestClientRole model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $restClientId
     * @param integer $roleRestId
     * @return mixed
     */
    public function actionUpdate($restClientId, $roleRestId)
    {
    	$model = $this->findModel($restClientId, $roleRestId);
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
    				return $this->redirect(['view', 'restClientId' => $model->restClientId, 'roleRestId' => $model->roleRestId]);
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
     * Deletes an existing RestClientRole model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $restClientId
     * @param integer $roleRestId
     * @return mixed
     */
    public function actionDelete($restClientId, $roleRestId)
    {
    	$model = $this->findModel($restClientId, $roleRestId);
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
     * Finds the RestClientRole model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $restClientId
     * @param integer $roleRestId
     * @return RestClientRole the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($restClientId, $roleRestId)
    {
        if (($model = RestClientRole::findOne(['restClientId' => $restClientId, 'roleRestId' => $roleRestId])) !== null) {
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
    public function actionAjaxGet($restClientId, $roleRestId)
    {
    	if (($model = RestClientRole::findOne(['restClientId' => $restClientId, 'roleRestId' => $roleRestId])) !== null) 
    	{
	    	return json_encode([
    			'value' => ['restClientId' => $restClientId, 'roleRestId' => $roleRestId], 
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
		$labelFields = explode(",", RestClientRole::$__AJAX_LABEL_FIELDS_COMMA_SEPARATED);
		$labelFormat = RestClientRole::$__AJAX_LABEL_FORMAT;
		
    	$rows = [];
    	if ($term !== null)
    	{
    		$qry = RestClientRole::find();
    		
    		$qry->orWhere('restClientId=:restClientId', [':restClientId' => $term]);
    		

    		$qry->orWhere('roleRestId=:roleRestId', [':roleRestId' => $term]);
    		
    		foreach ($labelFields as $field)
    			$qry->orWhere($field . ' LIKE :term', [':term' => "%{$term}%"]);
    		$allRows = $qry->limit(100)->all();
    		foreach ($allRows as $row) 
    		{
    			$rows[] = [
    				'value' => $row->restClientId,
    				'label' => $row->obtainFKLabel($labelFields, $labelFormat),
    				'data' => $row->toArray(),
    			];
    		}
    	}
    	return json_encode($rows);
    }
}
