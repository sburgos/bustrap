<?php
namespace orm\admin;

use Yii;

/**
 * This is the model class for table "usmp_admin.role_rest_permit".
 *
 * @property integer $id
 * @property integer $roleRestId
 * @property integer $priority	Number: 1 = Evaluates first, 100 = Evaluates later on
 * @property string $regex	Regular expression to match route. Eg. demo/(admin|cine)/crud* to match /demo/admin/crud... and /demo/cine/crud...
 * @property string $type	If the regex matches the requested url then we should allow or deny access.
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class RoleRestPermit extends \yii\db\ActiveRecord
{
	public static $__AJAX_LABEL_FIELDS_COMMA_SEPARATED = 'regex';
	public static $__AJAX_LABEL_FORMAT = "%s";
	
	
	const TYPE_ALLOW = 'allow';
	const TYPE_DENY = 'deny';

	/**
	 * This is a function place just so that gettext can
	 * parse this strings as messages
	 */
	private function __translationsForGetText()
	{
		'allow';
		'deny';
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
										
		$fields['typeLabel'] = function($row){
			if (empty($row->type)) return $row->type;
			return $row->type;
		};
				
		return $fields;
	}
	
	/**
	 * Set behaviors
	 */
	public function behaviors()
	{
		return [
			'timestamp' => [
				'class' => \yii\behaviors\TimestampBehavior::className(),
				'value' => function () { return date('Y-m-d H:i:s'); },
				'attributes' => [
					\yii\db\BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
					\yii\db\BaseActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
				],
			],
			'blameable' => [
				'class' => \common\yii\behaviors\BlameableBehavior::className(),
				'attributes' => [
					\yii\db\BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_by', 'updated_by'],
					\yii\db\BaseActiveRecord::EVENT_BEFORE_UPDATE => 'updated_by',
				],
			],
        ];
	}
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usmp_admin.role_rest_permit';
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
			'inarray_type' => ['type', 'in', 'range' => [self::TYPE_ALLOW, self::TYPE_DENY]],
			'trim' => [['regex', 'type', 'created_by', 'updated_by'], 'trim'],
			'required' => [['roleRestId', 'regex', 'type'], 'required'],
			'integer' => [['roleRestId', 'priority'], 'integer'],
			'string' => [['type'], 'string'],
			'safe' => [['created_at', 'updated_at'], 'safe'],
			'string_255' => [['regex'], 'string', 'max' => 255],
			'string_20' => [['created_by', 'updated_by'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'roleRestId' => 'Role Rest ID',
            'priority' => 'Priority',
            'regex' => 'Regex',
            'type' => 'Type',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
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
    		$labelFields = ['regex'];
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
    
}
