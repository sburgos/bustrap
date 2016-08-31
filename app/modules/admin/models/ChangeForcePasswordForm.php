<?php
namespace modules\admin\models;

use Yii;
use yii\base\Model;

class ChangeForcePasswordForm extends Model
{
	public $user = null;
	
    public $newPassword;
    
    public $checkPassword;

    /**
     * Construct by passing the user to which you want
     * to change the password
     * 
     * @param \orm\admin\UserAdmin $user
     * @param array $config
     */
    public function __construct($user, $config = [])
    {
    	if ($user && $user instanceof \orm\admin\UserAdmin)
    		$this->user = $user;
    	parent::__construct($config);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['newPassword', 'checkPassword'], 'required'],
        	[['newPassword', 'checkPassword'], 'string', 'min' => \orm\admin\UserAdmin::USER_PASSWORD_MIN_LENGTH],
            ['checkPassword', 'compare', 'compareAttribute' => 'newPassword'],
        ];
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
