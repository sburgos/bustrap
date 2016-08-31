<?php

namespace modules\admin\models;

use Yii;
use yii\base\Model;
use orm\admin\UserAdmin as OrmUser;

class ChangePasswordForm extends Model
{
	protected $user = null;
	
    public $currentPassword;
    
    public $newPassword;
    
    public $checkPassword;

    /**
     * Construct by passing the user to which you want
     * to change the password
     * 
     * @param OrmUser $user
     * @param array $config
     */
    public function __construct($user, $config = [])
    {
    	if ($user && $user instanceof OrmUser)
    		$this->user = $user;
    	parent::__construct($config);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'checkPassword'], 'required'],
        	[['newPassword', 'checkPassword'], 'string', 'min' => OrmUser::USER_PASSWORD_MIN_LENGTH],
            ['checkPassword', 'compare', 'compareAttribute' => 'newPassword'],
        	['currentPassword', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) 
        {
            if (!$this->user || !$this->user->verifyUserPassword($this->currentPassword)) 
            {
                $this->addError($attribute, "Incorrect password");
            }
        }
    }

    /**
     * Save the user password
     * 
     * @return boolean whether the password was saved
     */
    public function applyChanges()
    {
        if (!$this->validate())
        	return false;
         
        $this->user->userPassword = $this->newPassword;
		if (!$this->user->save()) 
		{
			$this->addError('newPassword', "The new password cannot be saved");
			return false;
		}
        
		return true;
    }
}
