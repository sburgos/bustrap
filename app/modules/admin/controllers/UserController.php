<?php
namespace modules\admin\controllers;

use modules\admin\models\UserAdminIdentity;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use modules\admin\models\LoginForm;
use common\yii\helpers\StringHelper;
use modules\admin\models\ChangePasswordForm;
use modules\admin\models\ChangeForcePasswordForm;
use orm\admin\UserAdmin;

class UserController extends Controller
{   
    /**
     * Allow to change any users password
     * 
     * @return Ambigous <string, string>
     */
    public function actionChangePassword()
    {
    	$user = UserAdmin::findOne(Yii::$app->request->get('id'));
    	$model = new ChangeForcePasswordForm($user);
    	if ($model->load(Yii::$app->request->post()) && $model->applyChanges()) 
    	{
    		Yii::$app->getSession()->setFlash('success', 
    			"The user password has been changed"
    		);
    		return $this->redirect(["/admin/crud/user-admin/view", 'id' => $user->id]);
    	}
    	
    	$this->layout = '@app/layouts/layout-popup-sm';
    	return $this->render('change-password', ['model' => $model]);
    }
    
    /**
     * Allow to login as any other user
     * 
     * @return Ambigous <string, string>
     */
    public function actionLoginAsUser()
    {
    	$user = UserAdmin::findOne(Yii::$app->request->get('id'));
    	if ($user && Yii::$app->getRequest()->isPost)
    	{
    		Yii::$app->user->login(UserAdminIdentity::findIdentity($user->id));
    		return $this->redirect(["/admin/index/index"]);
    	}
    	
    	$this->layout = '@app/layouts/layout-popup-sm';
    	return $this->render('login-as-user', ['model' => $user]);
    }
}
