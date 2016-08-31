<?php
namespace modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use modules\admin\models\LoginForm;
use orm\admin\UserAdmin;
use common\yii\helpers\StringHelper;
use modules\admin\models\ChangePasswordForm;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;

class IndexController extends Controller
{
	/**
	 * (non-PHPdoc)
	 * @see \yii\base\Component::behaviors()
	 */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Add the error handler
     * 
     * (non-PHPdoc)
     * @see \yii\base\Controller::actions()
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            	'defaultMessage' => 'Oops something went wrong. We have been notified and will try to fix this as soon as possible',
            ],
        ];
    }
    
    /**
     * Page to display when in maintenance mode
     * 
     * @return Ambigous <string, string>
     */
    public function actionMaintenance()
    {
    	$this->layout = '@app/layouts/layout-popup-md';
    	return $this->render('maintenance', []);
    }

    /**
     * Render the main dashboard page
     * 
     * @return Ambigous <string, string>
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /**
     * Allow the user to change his password
     * 
     * @return Ambigous <string, string>
     */
    public function actionChangePassword()
    {
    	$user = UserAdmin::findOne(Yii::$app->user->id);
    	$model = new ChangePasswordForm($user);
    	if ($model->load(Yii::$app->request->post()) && $model->applyChanges()) 
    	{
    		Yii::$app->getSession()->setFlash('success', 
    			"Your password has been changed"
    		);
    		return $this->redirect(["/"]);
    	}
    	
    	$this->layout = '@app/layouts/layout-popup-sm';
    	return $this->render('change-password', ['model' => $model]);
    }

    /**
     * Render the login form
     * 
     * @return \yii\web\Response|Ambigous <string, string>
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
        	$this->layout = '@app/layouts/layout-popup-sm';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout the user
     * 
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
