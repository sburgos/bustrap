<?php

namespace orm\admin\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use orm\admin\RestClientRole;

/**
 * SearchRestClientRole represents the model behind the search form about `orm\admin\RestClientRole`.
 */
class SearchRestClientRole extends RestClientRole
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
            [['restClientId'], 'safe'],
            [['roleRestId'], 'integer'],
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
        $query = RestClientRole::find();
        if (!empty($with)) $query->with($with);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'roleRestId' => $this->roleRestId,
        ]);

        $query->andFilterWhere(['like', 'restClientId', $this->restClientId]);

        return $dataProvider;
    }
}
