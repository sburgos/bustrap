<?php
namespace orm\bus;

use Yii;

/**
 * This is the model class for table "smart_node".
 *
 * @property integer $id
 * @property string $latitude
 * @property string $longitude
 *
 * @property Data[] $datas
 * @property SmartRoutes[] $smartRoutes
 */
class SmartNode extends \yii\db\ActiveRecord
{
	public static $__AJAX_LABEL_FIELDS_COMMA_SEPARATED = 'latitude';
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
        return 'smart_node';
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
			'trim' => [['latitude', 'longitude'], 'trim'],
			'required' => [['latitude', 'longitude'], 'required'],
			'string_45' => [['latitude', 'longitude'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
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
    		$labelFields = ['latitude'];
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
    public function getDatas()
    {
        return $this->hasMany(Data::className(), ['idSmartNode' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmartRoutes()
    {
        return $this->hasMany(SmartRoutes::className(), ['idSmartNode' => 'id']);
    }
    
}
