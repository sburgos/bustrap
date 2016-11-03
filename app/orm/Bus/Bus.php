<?php
namespace orm\bus;

use Yii;

/**
 * This is the model class for table "bus".
 *
 * @property integer $id
 * @property integer $idLine
 * @property string $name
 * @property string $busImage
 * @property string $extraInfo
 * @property boolean $active
 * @property string $mode
 *
 * @property Line $idLine0
 */
class Bus extends \yii\db\ActiveRecord
{
	public static $__AJAX_LABEL_FIELDS_COMMA_SEPARATED = 'name';
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
        return 'bus';
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
			'trim' => [['name', 'busImage', 'extraInfo', 'mode'], 'trim'],
			'required' => [['idLine', 'name', 'busImage', 'extraInfo', 'mode'], 'required'],
			'integer' => [['idLine'], 'integer'],
			'string' => [['extraInfo'], 'string'],
			'boolean' => [['active'], 'boolean'],
			'string_200' => [['name', 'busImage'], 'string', 'max' => 200],
			'string_100' => [['mode'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idLine' => 'Id Line',
            'name' => 'Name',
            'busImage' => 'Bus Image',
            'extraInfo' => 'Extra Info',
            'active' => 'Active',
            'mode' => 'Mode',
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
    		$labelFields = ['name'];
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
    public function getIdLine0()
    {
        return $this->hasOne(Line::className(), ['id' => 'idLine']);
    }
    
}
