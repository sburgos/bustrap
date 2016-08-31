<?php
namespace orm\event;

use Yii;

/**
 * This is the model class for table "usmp.culqi".
 *
 * @property integer $id
 * @property integer $ticketId
 * @property string $infoJson
 *
 * @property Ticket $ticket
 */
class Culqi extends \yii\db\ActiveRecord
{
	public static $__AJAX_LABEL_FIELDS_COMMA_SEPARATED = 'infoJson';
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
        return 'usmp.culqi';
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
			'json' => [['infoJson'], '\\common\\yii\\validators\\FilterJsonEncode'],
			'trim' => [['infoJson'], 'trim'],
			'required' => [['ticketId', 'infoJson'], 'required'],
			'integer' => [['ticketId'], 'integer'],
			'string' => [['infoJson'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ticketId' => 'Ticket ID',
            'infoJson' => 'Info Json',
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
    		$labelFields = ['infoJson'];
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
	 * Get the value of InfoJson already json decoded.
	 *
	 * @return [] | object | mixed
	 */
	public function getInfo()
	{
		if (empty($this->infoJson))
			return null;
		return @json_decode($this->infoJson, true);
	}
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicket()
    {
        return $this->hasOne(Ticket::className(), ['id' => 'ticketId']);
    }
    
}
