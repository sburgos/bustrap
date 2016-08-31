<?php

namespace orm\admin\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use orm\admin\RoleAdminPermit;

/**
 * SearchRoleAdminPermit represents the model behind the search form about `orm\admin\RoleAdminPermit`.
 */
class SearchRoleAdminPermit extends RoleAdminPermit
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
            [['id', 'roleAdminId', 'priority'], 'integer'],
            [['regex', 'type', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = RoleAdminPermit::find();
        if (!empty($with)) $query->with($with);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'roleAdminId' => $this->roleAdminId,
            'priority' => $this->priority,
        ]);

        $query->andFilterWhere(['like', 'regex', $this->regex])
            ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
