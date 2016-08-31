<?php

namespace orm\admin\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use orm\admin\UserAdminRole;

/**
 * SearchUserAdminRole represents the model behind the search form about `orm\admin\UserAdminRole`.
 */
class SearchUserAdminRole extends UserAdminRole
{
	/**
	 * Disable behaviors for search model
	 *
     * @inheritdoc
     */
	public function behaviors()
    {
        return [];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userAdminId', 'roleAdminId'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param array $with // relation names to fetch
     *
     * @return ActiveDataProvider
     */
    public function search($params, $with = null)
    {
        $query = UserAdminRole::find();
        if (!empty($with)) $query->with($with);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'userAdminId' => $this->userAdminId,
            'roleAdminId' => $this->roleAdminId,
        ]);

        return $dataProvider;
    }
}
