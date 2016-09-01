<?php
namespace orm\bus;

use Yii;

/**
 * This is the model class for table "line".
 *
 * @property integer $id
 * @property string $name
 * @property boolean $active
 *
 * @property Bus[] $buses
 * @property Route[] $routes
 */
class Line extends \yii\db\ActiveRecord
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
        return 'line';
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
			'trim' => [['name'], 'trim'],
			'required' => [['name'], 'required'],
			'boolean' => [['active'], 'boolean'],
			'string_45' => [['name'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'active' => 'Active',
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
    public function getBuses()
    {
        return $this->hasMany(Bus::className(), ['idLine' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes()
    {
        return $this->hasMany(Route::className(), ['lineId' => 'id']);
    }
    
}
