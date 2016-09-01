<?php
namespace orm\bus;

use Yii;

/**
 * This is the model class for table "data".
 *
 * @property integer $id
 * @property integer $idSmartNode
 * @property string $moveDate
 * @property double $velocity
 * @property boolean $isTraffic
 *
 * @property SmartNode $idSmartNode0
 */
class Data extends \yii\db\ActiveRecord
{
	public static $__AJAX_LABEL_FIELDS_COMMA_SEPARATED = 'moveDate';
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
        return 'data';
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
			'date-moveDate' => ['moveDate', '\common\yii\validators\DateValidator', 'format' => 'php:Y-m-d H:i:s'],
			'required' => [['idSmartNode', 'moveDate', 'velocity'], 'required'],
			'integer' => [['idSmartNode'], 'integer'],
			'safe' => [['moveDate'], 'safe'],
			'number' => [['velocity'], 'number'],
			'boolean' => [['isTraffic'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idSmartNode' => 'Id Smart Node',
            'moveDate' => 'Move Date',
            'velocity' => 'Velocity',
            'isTraffic' => 'Is Traffic',
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
    		$labelFields = ['moveDate'];
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
    public function getIdSmartNode0()
    {
        return $this->hasOne(SmartNode::className(), ['id' => 'idSmartNode']);
    }
    
}
