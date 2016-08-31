<?php
namespace orm\event;

use Yii;

/**
 * This is the model class for table "usmp.price".
 *
 * @property integer $id
 * @property integer $eventId
 * @property string $name
 * @property string $description
 * @property string $currency
 * @property integer $stock
 * @property string $toDate
 * @property string $fromDate
 *
 * @property Event $event
 * @property Share[] $shares
 */
class Price extends \yii\db\ActiveRecord
{
	public static $__AJAX_LABEL_FIELDS_COMMA_SEPARATED = 'name';
	public static $__AJAX_LABEL_FORMAT = "%s";
	
	
	const CURRENCY_SOLES = 'soles';
	const CURRENCY_DOLARES = 'dolares';

	/**
	 * This is a function place just so that gettext can
	 * parse this strings as messages
	 */
	private function __translationsForGetText()
	{
		'soles';
		'dolares';
	}
	
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
										
		$fields['currencyLabel'] = function($row){
			if (empty($row->currency)) return $row->currency;
			return $row->currency;
		};
				
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
        return 'usmp.price';
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
			'inarray_currency' => ['currency', 'in', 'range' => [self::CURRENCY_SOLES, self::CURRENCY_DOLARES]],
			'trim' => [['name', 'description', 'currency'], 'trim'],
			'date-toDate' => ['toDate', '\common\yii\validators\DateValidator', 'format' => 'php:Y-m-d H:i:s'],
			'date-fromDate' => ['fromDate', '\common\yii\validators\DateValidator', 'format' => 'php:Y-m-d H:i:s'],
			'required' => [['eventId', 'name', 'description', 'currency', 'stock', 'toDate', 'fromDate'], 'required'],
			'integer' => [['eventId', 'stock'], 'integer'],
			'string' => [['currency'], 'string'],
			'safe' => [['toDate', 'fromDate'], 'safe'],
			'string_200' => [['name'], 'string', 'max' => 200],
			'string_300' => [['description'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'eventId' => 'Event ID',
            'name' => 'Name',
            'description' => 'Description',
            'currency' => 'Currency',
            'stock' => 'Stock',
            'toDate' => 'To Date',
            'fromDate' => 'From Date',
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
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['id' => 'eventId']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShares()
    {
        return $this->hasMany(Share::className(), ['priceId' => 'id']);
    }
    
}
