<?php
namespace orm\bus;

use Yii;

/**
 * This is the model class for table "agenda".
 *
 * @property integer $id
 * @property string $titulo
 * @property string $descripcion
 * @property string $lugar
 * @property string $desdeDate
 * @property string $hastaDate
 * @property boolean $active
 *
 * @property Invitados[] $invitados
 */
class Agenda extends \yii\db\ActiveRecord
{
	public static $__AJAX_LABEL_FIELDS_COMMA_SEPARATED = 'titulo';
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
        return 'agenda';
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
			'trim' => [['titulo', 'descripcion', 'lugar'], 'trim'],
			'date-desdeDate' => ['desdeDate', '\common\yii\validators\DateValidator', 'format' => 'php:Y-m-d H:i:s'],
			'date-hastaDate' => ['hastaDate', '\common\yii\validators\DateValidator', 'format' => 'php:Y-m-d H:i:s'],
			'required' => [['titulo', 'descripcion', 'lugar', 'desdeDate', 'hastaDate'], 'required'],
			'string' => [['descripcion'], 'string'],
			'safe' => [['desdeDate', 'hastaDate'], 'safe'],
			'boolean' => [['active'], 'boolean'],
			'string_200' => [['titulo'], 'string', 'max' => 200],
			'string_400' => [['lugar'], 'string', 'max' => 400],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'titulo' => 'Titulo',
            'descripcion' => 'Descripcion',
            'lugar' => 'Lugar',
            'desdeDate' => 'Desde Date',
            'hastaDate' => 'Hasta Date',
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
    		$labelFields = ['titulo'];
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
    public function getInvitados()
    {
        return $this->hasMany(Invitados::className(), ['agendaId' => 'id']);
    }
    
}
