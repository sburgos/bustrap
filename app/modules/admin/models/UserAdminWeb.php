<?php
namespace modules\admin\models;

use common\yii\helpers\StringHelper;

class UserAdminWeb extends \yii\web\User
{	
	protected $_access = [];
	
	/**
	 * Override the \yii\web\User can so it checks permits
	 * based on the user roles instead of checking based on the
	 * user id
	 * 
	 * (non-PHPdoc)
	 * @see \yii\web\User::can()
	 */
	public function can($permissionName, $params = [], $allowCaching = true)
    {
    	// If specifying an array then get the first value
    	if (is_array($permissionName))
    		$permissionName = $permissionName[0];
    	
        if ($allowCaching && empty($params) && isset($this->_access[$permissionName])) {
            return $this->_access[$permissionName];
        }
        $access = $this->__canAccess($permissionName, $params);
        if ($allowCaching && empty($params)) {
            $this->_access[$permissionName] = $access;
        }

        return $access;
    }
    
    /**
     * Check if the user can access a specific permission
     * 
     * @param string $permissionName
     * @param array $params
     * @return boolean
     */
    public function __canAccess($permissionName, $params)
    {
    	// Allow accessing debug and gii on test environments	
    	if (!YII_ENV_PROD)
    	{
    		if (StringHelper::startsWith($permissionName, "debug"))
    			return true;
    		if (StringHelper::startsWith($permissionName, "gii"))
    			return true;
    	}
    	
    	// If is a guest then deny
    	if ($this->isGuest)
    		return false;
    	
    	// Get the user allowed and deny rules
    	return $this->identity->can($permissionName);
    }
}