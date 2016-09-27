<?php
namespace orm\bus;

use Yii;

/**
 * This is the model class for table "invitados".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $correo
 * @property string $skype
 * @property boolean $active
 * @property integer $agendaId
 *
 * @property Agenda $agenda
 */
class Invitados extends \yii\db\ActiveRecord
{
	public static $__AJAX_LABEL_FIELDS_COMMA_SEPARATED = 'nombre';
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
        return 'invitados';
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
			'trim' => [['nombre', 'correo', 'skype'], 'trim'],
			'required' => [['nombre', 'correo', 'skype', 'agendaId'], 'required'],
			'boolean' => [['active'], 'boolean'],
			'integer' => [['agendaId'], 'integer'],
			'string_200' => [['nombre', 'correo', 'skype'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'correo' => 'Correo',
            'skype' => 'Skype',
            'active' => 'Active',
            'agendaId' => 'Agenda ID',
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
    		$labelFields = ['nombre'];
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
    public function getAgenda()
    {
        return $this->hasOne(Agenda::className(), ['id' => 'agendaId']);
    }
    
}
