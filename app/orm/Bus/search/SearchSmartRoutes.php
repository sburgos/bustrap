<?php

namespace orm\bus\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use orm\bus\SmartRoutes;

/**
 * SearchSmartRoutes represents the model behind the search form about `orm\bus\SmartRoutes`.
 */
class SearchSmartRoutes extends SmartRoutes
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
            [['id', 'idRoute', 'idSmartNode'], 'integer'],
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
        $query = SmartRoutes::find();
        if (!empty($with)) $query->with($with);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idRoute' => $this->idRoute,
            'idSmartNode' => $this->idSmartNode,
        ]);

        return $dataProvider;
    }
}
