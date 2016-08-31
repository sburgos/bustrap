<?php
namespace orm\event;

use Yii;

/**
 * This is the model class for table "usmp.ticket".
 *
 * @property integer $id
 * @property integer $eventId
 * @property integer $asistantId
 * @property string $sellingDate
 * @property boolean $status
 *
 * @property Assistant $asistant
 * @property Event $event
 * @property TicketLog[] $ticketLogs
 * @property TicketPrices[] $ticketPrices
 */
class Ticket extends \yii\db\ActiveRecord
{
	public static $__AJAX_LABEL_FIELDS_COMMA_SEPARATED = 'sellingDate';
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
        return 'usmp.ticket';
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
			'date-sellingDate' => ['sellingDate', '\common\yii\validators\DateValidator', 'format' => 'php:Y-m-d H:i:s'],
			'required' => [['eventId', 'asistantId', 'sellingDate'], 'required'],
			'integer' => [['eventId', 'asistantId'], 'integer'],
			'safe' => [['sellingDate'], 'safe'],
			'boolean' => [['status'], 'boolean'],
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
            'asistantId' => 'Asistant ID',
            'sellingDate' => 'Selling Date',
            'status' => 'Status',
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
    		$labelFields = ['sellingDate'];
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
    public function getAsistant()
    {
        return $this->hasOne(Assistant::className(), ['id' => 'asistantId']);
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
    public function getTicketLogs()
    {
        return $this->hasMany(TicketLog::className(), ['ticketId' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicketPrices()
    {
        return $this->hasMany(TicketPrices::className(), ['ticketId' => 'id']);
    }
    
}
