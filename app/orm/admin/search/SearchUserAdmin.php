<?php

namespace orm\admin\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use orm\admin\UserAdmin;

/**
 * SearchUserAdmin represents the model behind the search form about `orm\admin\UserAdmin`.
 */
class SearchUserAdmin extends UserAdmin
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
            [['id'], 'integer'],
            [['username', 'displayName', 'secretToken', 'authToken', 'userPassword', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['active'], 'boolean'],
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
        $query = UserAdmin::find();
        if (!empty($with)) $query->with($with);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'displayName', $this->displayName])
            ->andFilterWhere(['like', 'secretToken', $this->secretToken])
            ->andFilterWhere(['like', 'authToken', $this->authToken])
            ->andFilterWhere(['like', 'userPassword', $this->userPassword]);

        return $dataProvider;
    }
}
