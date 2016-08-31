<?php
namespace orm\admin;

use Yii;

/**
 * This is the model class for table "usmp_admin.user_admin".
 *
 * @property integer $id
 * @property string $username	Used for login
 * @property string $displayName	Name to display
 * @property string $secretToken	Random generated string to protect the account. This is used internally.
 * @property string $authToken	Token for authentication for Yii. This is what is used to create the cookies and other Yii stuff.
 * @property string $userPassword	The password hashed with bCrypt.
 * @property boolean $active	If the account is active for login.
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class UserAdmin extends \yii\db\ActiveRecord
{
	public static $__AJAX_LABEL_FIELDS_COMMA_SEPARATED = 'username';
	public static $__AJAX_LABEL_FORMAT = "%s";
	
	
	const USER_PASSWORD_MIN_LENGTH = 6;
	
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
				'userPassword',
				'secretToken',
				'authToken',
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
			'passwords' => [
				'class' => \common\yii\behaviors\PasswordBehavior::className(),
				'columns' => ['userPassword'],
			],
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
        return 'usmp_admin.user_admin';
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
			'trim' => [['username', 'displayName', 'secretToken', 'authToken', 'userPassword', 'created_by', 'updated_by'], 'trim'],
			'password-userPassword' => ['userPassword', 'string', 'min' => static::USER_PASSWORD_MIN_LENGTH],
			'required' => [['username', 'displayName', 'secretToken', 'authToken', 'userPassword'], 'required'],
			'boolean' => [['active'], 'boolean'],
			'safe' => [['created_at', 'updated_at'], 'safe'],
			'string_50' => [['username', 'displayName'], 'string', 'max' => 50],
			'string_32' => [['secretToken', 'authToken'], 'string', 'max' => 32],
			'string_255' => [['userPassword'], 'string', 'max' => 255],
			'string_20' => [['created_by', 'updated_by'], 'string', 'max' => 20],
			'unique_username' => [['username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'displayName' => 'Display Name',
            'secretToken' => 'Secret Token',
            'authToken' => 'Auth Token',
            'userPassword' => 'User Password',
            'active' => 'Active',
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
    		$labelFields = ['username'];
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
	 * Verify the password UserPassword.
	 * The idea is that you can pass a plain password string and this
	 * will check if it is a valid password
	 */
	public function verifyUserPassword($plainPassword)
	{
		if (empty($plainPassword)) return false;
		return \Yii::$app->getSecurity()->validatePassword(
			$plainPassword, 
			$this->userPassword
		);
	}
	
}
