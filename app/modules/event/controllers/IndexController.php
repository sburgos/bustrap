<?php

namespace modules\event\controllers;

use Yii;
use common\yii\gii\generators\crud\event\CrudEvent;

/**
 * IndexController displays a home page for the CRUD
 */
class IndexController extends \yii\web\Controller
{
    /**
     * Lists all Movie models.
     * @return mixed
     */
    public function actionIndex()
    {
        $allLinks = [];
        $event = new CrudEvent(['moduleId' => 'event', 'links' => $allLinks]);
        Yii::$app->trigger(CrudEvent::FILTER_INDEX_PAGE_LINKS, $event);

        return $this->render('index', [
            'links' => $event->links,
            'moduleId' => 'event',
        ]);
    }
}
