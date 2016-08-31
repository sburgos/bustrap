<?php
namespace common\yii\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use yii\web\UploadedFile;
use common\yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class PasswordBehavior extends Behavior
{
	public $columns = [];
	
	public function events()
	{
		return [
			BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
			BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
		];
	}
	
	/**
	 * Make sure that the password is stored as a hash
	 * on insert always do the password generation hash
	 * 
     * @param Event $event
     * @inheritdoc
     */
    public function beforeInsert($event)
    {
    	$model = $event->sender;
    	foreach ($this->columns as $col)
    	{
    		if (!empty($model->{$col}))
    			$model->{$col} = \Yii::$app->getSecurity()->generatePasswordHash($model->{$col});
    	}
    }
	
	/**
	 * Make sure that the password is stored as a hash
	 * on insert always do the password generation hash
	 * 
     * @param Event $event
     * @inheritdoc
     */
    public function beforeUpdate($event)
    {
    	/** @var $model \yii\db\ActiveRecord */
    	$model = $event->sender;
    	$dirtyColumns = $model->getDirtyAttributes();
    	foreach ($this->columns as $col)
    	{
    		if (array_key_exists($col, $dirtyColumns))
    		{
    			if (!empty($model->{$col}))
    				$model->{$col} = \Yii::$app->getSecurity()->generatePasswordHash($model->{$col});
    		}
    	}
    }
}