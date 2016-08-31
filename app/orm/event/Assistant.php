<?php
namespace orm\event;

use Yii;

/**
 * This is the model class for table "usmp.assistant".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $dni
 * @property integer $eventId
 * @property boolean $status
 *
 * @property Event $event
 * @property Ticket[] $tickets
 */
class Assistant extends \yii\db\ActiveRecord
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
        return 'usmp.assistant';
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
			'trim' => [['name', 'email', 'dni'], 'trim'],
			'required' => [['name', 'email', 'dni', 'eventId'], 'required'],
			'integer' => [['eventId'], 'integer'],
			'boolean' => [['status'], 'boolean'],
			'string_45' => [['name'], 'string', 'max' => 45],
			'string_200' => [['email'], 'string', 'max' => 200],
			'string_15' => [['dni'], 'string', 'max' => 15],
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
            'email' => 'Email',
            'dni' => 'Dni',
            'eventId' => 'Event ID',
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
    public function getTickets()
    {
        return $this->hasMany(Ticket::className(), ['asistantId' => 'id']);
    }
    
}
