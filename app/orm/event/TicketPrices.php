<?php
namespace orm\event;

use Yii;

/**
 * This is the model class for table "usmp.ticket_prices".
 *
 * @property integer $id
 * @property integer $ticketId
 * @property integer $priceId
 * @property string $priceName
 * @property integer $shareId
 * @property string $shareName
 * @property string $currency
 * @property double $amount
 * @property boolean $paid
 *
 * @property Ticket $ticket
 */
class TicketPrices extends \yii\db\ActiveRecord
{
	public static $__AJAX_LABEL_FIELDS_COMMA_SEPARATED = 'priceName';
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
        return 'usmp.ticket_prices';
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
			'trim' => [['priceName', 'shareName', 'currency'], 'trim'],
			'required' => [['ticketId', 'priceId', 'priceName', 'shareId', 'shareName', 'currency', 'amount'], 'required'],
			'integer' => [['ticketId', 'priceId', 'shareId'], 'integer'],
			'string' => [['currency'], 'string'],
			'number' => [['amount'], 'number'],
			'boolean' => [['paid'], 'boolean'],
			'string_200' => [['priceName', 'shareName'], 'string', 'max' => 200],
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
            'priceId' => 'Price ID',
            'priceName' => 'Price Name',
            'shareId' => 'Share ID',
            'shareName' => 'Share Name',
            'currency' => 'Currency',
            'amount' => 'Amount',
            'paid' => 'Paid',
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
    		$labelFields = ['priceName'];
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
    public function getTicket()
    {
        return $this->hasOne(Ticket::className(), ['id' => 'ticketId']);
    }
    
}
