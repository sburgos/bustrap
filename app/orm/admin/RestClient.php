<?php
namespace orm\admin;

use Yii;

/**
 * This is the model class for table "usmp_admin.rest_client".
 *
 * @property string $id	E.g. IOS_CINEPAPAYA_CONTENT
 * @property string $secretToken	The secret token that will be used to allow access with this rest client id.
 * @property string $displayName	Name for internal identification purposes.
 * @property string $platform	E.g. iOS, Android, Web, Kiosk, etc.
 * @property string $version	E.g. 1.0, 4.1, etc.
 * @property boolean $sendSiftFingerprint	If the fingerprint should be sent to sift science
 * @property boolean $active	If it should accept requests or not.
 * @property string $ownerName	Name of the owner of this rest client. Normally it is Cinepapaya but it can be Cinemark etc. New users will belong to the ownerName of the rest client invoked.
 * @property boolean $allowCreditCards	If credit cards can be used with this client
 * @property boolean $allowStoredCreditCards	If stored credit cards can be used with this client
 * @property boolean $allowPaypal	If paypal can be used with this rest client
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class RestClient extends \yii\db\ActiveRecord
{
	public static $__AJAX_LABEL_FIELDS_COMMA_SEPARATED = 'displayName';
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
				'secretToken',
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
        return 'usmp_admin.rest_client';
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
			'trim' => [['id', 'secretToken', 'displayName', 'platform', 'version', 'ownerName', 'created_by', 'updated_by'], 'trim'],
			'required' => [['id', 'secretToken', 'displayName', 'platform', 'version'], 'required'],
			'boolean' => [['sendSiftFingerprint', 'active', 'allowCreditCards', 'allowStoredCreditCards', 'allowPaypal'], 'boolean'],
			'safe' => [['created_at', 'updated_at'], 'safe'],
			'string_32' => [['id', 'secretToken', 'platform'], 'string', 'max' => 32],
			'string_50' => [['displayName', 'ownerName'], 'string', 'max' => 50],
			'string_10' => [['version'], 'string', 'max' => 10],
			'string_20' => [['created_by', 'updated_by'], 'string', 'max' => 20],
			'unique_secretToken' => [['secretToken'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'secretToken' => 'Secret Token',
            'displayName' => 'Display Name',
            'platform' => 'Platform',
            'version' => 'Version',
            'sendSiftFingerprint' => 'Send Sift Fingerprint',
            'active' => 'Active',
            'ownerName' => 'Owner Name',
            'allowCreditCards' => 'Allow Credit Cards',
            'allowStoredCreditCards' => 'Allow Stored Credit Cards',
            'allowPaypal' => 'Allow Paypal',
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
    		$labelFields = ['displayName'];
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
