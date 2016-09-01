<?php

namespace orm\bus\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use orm\bus\Route;

/**
 * SearchRoute represents the model behind the search form about `orm\bus\Route`.
 */
class SearchRoute extends Route
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
            [['id', 'lineId'], 'integer'],
            [['latitude', 'longitude'], 'safe'],
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
        $query = Route::find();
        if (!empty($with)) $query->with($with);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'lineId' => $this->lineId,
        ]);

        $query->andFilterWhere(['like', 'latitude', $this->latitude])
            ->andFilterWhere(['like', 'longitude', $this->longitude]);

        return $dataProvider;
    }
}
