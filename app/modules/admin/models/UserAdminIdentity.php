<?php
namespace modules\admin\models;

use common\yii\helpers\ArrayHelper;
use yii\helpers\Url;
use orm\admin\RoleAdminPermit;

class UserAdminIdentity extends \orm\admin\UserAdmin implements \yii\web\IdentityInterface
{
	private $_permits = null;
	
	/**
	 * @inheritdoc
	 */
	public static function findIdentity($id)
	{
		if ($id === null || $id === false)
			return null;
		return static::findOne($id);
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		// Not implemented
		return null;
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username        	
	 * @return static|null
	 */
	public static function findByUsername($username)
	{
		if (empty($username))
			return null;
		return static::findOne(['username' => $username]);
	}

	/**
	 * @inheritdoc
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey()
	{
		return $this->authToken;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
	{
		return $this->authToken === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param string $password
	 *        	password to validate
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return $this->verifyUserPassword($password);
	}
	
	/**
	 * Get all of the user permits indexed by 
	 * 
	 * @return array
	 */
	public function getUserPermitsFlat()
	{
		if ($this->_permits === null)
		{
			// Attempt loading from cache
			$cacheKey = "user_flat_permits_id_" . $this->id;
			$cache = \Yii::$app->getCache();
			$cachedPermits = $cache->get($cacheKey);
			if ($cachedPermits !== false)
			{
				$this->_permits = $cachedPermits;
				return $this->_permits;
			}
			
			$this->_permits = [];
			
			// Get all of the permits for all the roles assigned to the user
			$userAdminId = $this->id;
			$allPermits = RoleAdminPermit::find()->innerJoinWith([
				'roleAdmin' => function($qry) use ($userAdminId) {
					$qry->innerJoinWith(['userAdminRoles' => function ($qry2) use ($userAdminId) {
						$qry2->where(['userAdminId' => $userAdminId]);
					}], false);
				},
			], false);
			
			// Sort from most important to least important
			$allPermits->orderBy([
				'priority' => SORT_ASC,
				'type' => SORT_DESC, // deny before allow when same priority
			]);
				
			// Build the list of permits
			foreach ($allPermits->asArray()->all() as $permit)
			{
				$regex = rtrim(ltrim(trim($permit['regex']), "/"), "/");
				if (!array_key_exists($regex, $this->_permits))
					$this->_permits[$regex] = ($permit['type'] == RoleAdminPermit::TYPE_ALLOW) ? true : false;
			}
			
			// Save the permits in cache
			$cache->set($cacheKey, $this->_permits, 3); // 5 minutes
		}
		return $this->_permits;
	}
	
	/**
	 * Determine if a user can access a specific uniqueId.
	 * 
	 * @param string $permitName
	 * @return boolean
	 */
	public function can($permitName)
	{
		// Do not allow for inactive users
		if (!$this->active)
			return false;
		
		// Check that something is set and is a string
		if (empty($permitName) || !is_string($permitName))
			return false;
		
		// Normalize permitName
		$permitName = rtrim(ltrim(trim($permitName), "/"), "/");
		
		// Allow accessing some common sites
		$basicRoutes = [
			//'admin/index/index' => true,
			'admin/index/change-password' => true,
			'admin/index/logout' => true,
		];
		if (array_key_exists($permitName, $basicRoutes))
			return true;
		
		// Check for permits
		$permits = $this->getUserPermitsFlat();
		foreach ($permits as $key => $allow)
		{
			// Exact match
			if ($key == $permitName)
				return $allow;
			
			// Regular expression match
			$pattern = "/^" . str_replace("*", ".*", str_replace("/", "\\/", $key)) . "$/";
			$match = preg_match($pattern, $permitName);
			if ($match === 1)
				return $allow;
		}
		
		// Not found then return false
		return false;
	}
}
