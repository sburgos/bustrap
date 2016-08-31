<?php
namespace orm\admin;

use Yii;

/**
 * This is the model class for table "usmp_admin.role_admin".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class RoleAdmin extends \yii\db\ActiveRecord
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
        return 'usmp_admin.role_admin';
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
			'trim' => [['name', 'description', 'created_by', 'updated_by'], 'trim'],
			'nullable' => [['description'], 'default'],
			'required' => [['name'], 'required'],
			'string' => [['description'], 'string'],
			'safe' => [['created_at', 'updated_at'], 'safe'],
			'string_50' => [['name'], 'string', 'max' => 50],
			'string_20' => [['created_by', 'updated_by'], 'string', 'max' => 20],
			'unique_name' => [['name'], 'unique'],
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
            'description' => 'Description',
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
    public function getRoleAdminPermits()
    {
        return $this->hasMany(RoleAdminPermit::className(), ['roleAdminId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAdminRoles()
    {
        return $this->hasMany(UserAdminRole::className(), ['roleAdminId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAdmins()
    {
        return $this->hasMany(UserAdmin::className(), ['id' => 'userAdminId'])->viaTable('papaya_admin.user_admin_role', ['roleAdminId' => 'id']);
    }


}
