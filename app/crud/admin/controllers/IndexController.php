<?php

namespace crud\admin\controllers;

use Yii;
use common\yii\gii\generators\crud\event\CrudEvent;

/**
 * IndexController displays a home page for the CRUD
 */
class IndexController extends \yii\web\Controller
{
    /**
     * Lists all UserAdmin models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$allLinks = [
			'rest-client' => ['label' => 'Rest Clients', 'url' => ['rest-client/index']],
			'rest-client-role' => ['label' => 'Rest Client Roles', 'url' => ['rest-client-role/index']],
			'role-admin' => ['label' => 'Role Admins', 'url' => ['role-admin/index']],
			'role-admin-permit' => ['label' => 'Role Admin Permits', 'url' => ['role-admin-permit/index']],
			'role-rest' => ['label' => 'Role Rests', 'url' => ['role-rest/index']],
			'role-rest-permit' => ['label' => 'Role Rest Permits', 'url' => ['role-rest-permit/index']],
			'user-admin' => ['label' => 'User Admins', 'url' => ['user-admin/index']],
			'user-admin-role' => ['label' => 'User Admin Roles', 'url' => ['user-admin-role/index']],
			'user-admin-session' => ['label' => 'User Admin Sessions', 'url' => ['user-admin-session/index']],
		];
    	$event = new CrudEvent(['moduleId' => 'admin', 'links' => $allLinks]);
    	Yii::$app->trigger(CrudEvent::FILTER_INDEX_PAGE_LINKS, $event); 
    	
        return $this->render('index', [
            'links' => $event->links,
            'moduleId' => 'admin',
        ]);
    }
}
