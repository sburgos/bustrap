<?php
namespace orm\event;

use Yii;

/**
 * This is the model class for table "usmp.event".
 *
 * @property integer $id
 * @property string $name
 * @property string $latitude
 * @property string $longitude
 * @property integer $stock
 * @property string $toDate
 * @property string $fromDate
 * @property string $image
 * @property string $description
 * @property integer $managerId
 * @property string $ticketText
 *
 * @property Assistant[] $assistants
 * @property Manager $manager
 * @property Price[] $prices
 * @property Ticket[] $tickets
 */
class Event extends \yii\db\ActiveRecord
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
        return 'usmp.event';
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
			'trim' => [['name', 'latitude', 'longitude', 'image', 'description', 'ticketText'], 'trim'],
			'nullable' => [['image', 'ticketText'], 'default'],
			'date-toDate' => ['toDate', '\common\yii\validators\DateValidator', 'format' => 'php:Y-m-d H:i:s'],
			'date-fromDate' => ['fromDate', '\common\yii\validators\DateValidator', 'format' => 'php:Y-m-d H:i:s'],
			'required' => [['name', 'latitude', 'longitude', 'stock', 'toDate', 'fromDate', 'description', 'managerId'], 'required'],
			'integer' => [['stock', 'managerId'], 'integer'],
			'safe' => [['toDate', 'fromDate'], 'safe'],
			'string' => [['description', 'ticketText'], 'string'],
			'string_300' => [['name'], 'string', 'max' => 300],
			'string_45' => [['latitude', 'longitude'], 'string', 'max' => 45],
			'string_200' => [['image'], 'string', 'max' => 200],
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
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'stock' => 'Stock',
            'toDate' => 'To Date',
            'fromDate' => 'From Date',
            'image' => 'Image',
            'description' => 'Description',
            'managerId' => 'Manager ID',
            'ticketText' => 'Ticket Text',
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
    public function getAssistants()
    {
        return $this->hasMany(Assistant::className(), ['eventId' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(Manager::className(), ['id' => 'managerId']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['eventId' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::className(), ['eventId' => 'id']);
    }
    
}
