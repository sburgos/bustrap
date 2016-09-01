<?php
namespace orm\bus;

use Yii;

/**
 * This is the model class for table "smart_routes".
 *
 * @property integer $id
 * @property integer $idRoute
 * @property integer $idSmartNode
 *
 * @property Route $idRoute0
 * @property SmartNode $idSmartNode0
 */
class SmartRoutes extends \yii\db\ActiveRecord
{
	public static $__AJAX_LABEL_FIELDS_COMMA_SEPARATED = 'id';
	public static $__AJAX_LABEL_FORMAT = "%s";
	
	
	/**
	 * Fields to include when doing toArray
	 */
	public function fields()
	{
		$fields = array_diff_key(
			parent::fields(), 
			array_flip([
				'created_at',
				'created_by',
				'updated_at',
				'updated_by',
			])
		);
										
		return $fields;
	}
	
	/**
	 * Set behaviors
	 */
	public function behaviors()
	{
		return [
        ];
	}
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'smart_routes';
    }

    /**
     * Rules have an index key so it is easy to check by rule type or
     * even remove rules or change them from inherited classes.
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
			'required' => [['idRoute', 'idSmartNode'], 'required'],
			'integer' => [['idRoute', 'idSmartNode'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idRoute' => 'Id Route',
            'idSmartNode' => 'Id Smart Node',
        ];
    }
    
    /**
     * Get the label to use on
     * foreign keys
     */
    public function obtainFKLabel($fields = null, $format = null)
    {
    	if (empty($fields))
    	{
    		$labelFields = ['id'];
			$labelFormat = "%s";
		}
		else
		{
			$labelFields = $fields;
			$labelFormat = $format;
		}
		
		$rowValues = [];
    	foreach ($labelFields as $field)
    		$rowValues[] = isset($this->{$field}) ? $this->{$field} : "";
    	return vsprintf($labelFormat, $rowValues);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdRoute0()
    {
        return $this->hasOne(Route::className(), ['id' => 'idRoute']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSmartNode0()
    {
        return $this->hasOne(SmartNode::className(), ['id' => 'idSmartNode']);
    }
    
}
