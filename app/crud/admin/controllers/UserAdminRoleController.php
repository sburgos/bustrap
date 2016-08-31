<?php

namespace crud\admin\controllers;

use Yii;
use orm\admin\UserAdminRole;
use orm\admin\search\SearchUserAdminRole;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\VarDumper;
use yii\filters\VerbFilter;

/**
 * UserAdminRoleController implements the CRUD actions for UserAdminRole model.
 */
class UserAdminRoleController extends Controller
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
     * Lists all UserAdminRole models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchUserAdminRole();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, null);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserAdminRole model.
     * @param integer $userAdminId
     * @param integer $roleAdminId
     * @return mixed
     */
    public function actionView($userAdminId, $roleAdminId)
    {
        return $this->render('view', [
            'model' => $this->findModel($userAdminId, $roleAdminId),
        ]);
    }
    /**
     * Creates a new UserAdminRole model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserAdminRole();
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
    				return $this->redirect(['view', 'userAdminId' => $model->userAdminId, 'roleAdminId' => $model->roleAdminId]);
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
     * Updates an existing UserAdminRole model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $userAdminId
     * @param integer $roleAdminId
     * @return mixed
     */
    public function actionUpdate($userAdminId, $roleAdminId)
    {
    	$model = $this->findModel($userAdminId, $roleAdminId);
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
    				return $this->redirect(['view', 'userAdminId' => $model->userAdminId, 'roleAdminId' => $model->roleAdminId]);
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
     * Deletes an existing UserAdminRole model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $userAdminId
     * @param integer $roleAdminId
     * @return mixed
     */
    public function actionDelete($userAdminId, $roleAdminId)
    {
    	$model = $this->findModel($userAdminId, $roleAdminId);
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
     * Finds the UserAdminRole model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $userAdminId
     * @param integer $roleAdminId
     * @return UserAdminRole the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($userAdminId, $roleAdminId)
    {
        if (($model = UserAdminRole::findOne(['userAdminId' => $userAdminId, 'roleAdminId' => $roleAdminId])) !== null) {
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
    public function actionAjaxGet($userAdminId, $roleAdminId)
    {
    	if (($model = UserAdminRole::findOne(['userAdminId' => $userAdminId, 'roleAdminId' => $roleAdminId])) !== null) 
    	{
	    	return json_encode([
    			'value' => ['userAdminId' => $userAdminId, 'roleAdminId' => $roleAdminId], 
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
		$labelFields = explode(",", UserAdminRole::$__AJAX_LABEL_FIELDS_COMMA_SEPARATED);
		$labelFormat = UserAdminRole::$__AJAX_LABEL_FORMAT;
		
    	$rows = [];
    	if ($term !== null)
    	{
    		$qry = UserAdminRole::find();
    		
    		$qry->orWhere('userAdminId=:userAdminId', [':userAdminId' => $term]);
    		

    		$qry->orWhere('roleAdminId=:roleAdminId', [':roleAdminId' => $term]);
    		
    		foreach ($labelFields as $field)
    			$qry->orWhere($field . ' LIKE :term', [':term' => "%{$term}%"]);
    		$allRows = $qry->limit(100)->all();
    		foreach ($allRows as $row) 
    		{
    			$rows[] = [
    				'value' => $row->userAdminId,
    				'label' => $row->obtainFKLabel($labelFields, $labelFormat),
    				'data' => $row->toArray(),
    			];
    		}
    	}
    	return json_encode($rows);
    }
}
