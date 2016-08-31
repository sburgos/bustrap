<?php

namespace orm\event\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use orm\event\Price;

/**
 * SearchPrice represents the model behind the search form about `orm\event\Price`.
 */
class SearchPrice extends Price
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
            [['id', 'eventId', 'stock'], 'integer'],
            [['name', 'description', 'currency', 'toDate', 'fromDate'], 'safe'],
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
        $query = Price::find();
        if (!empty($with)) $query->with($with);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'eventId' => $this->eventId,
            'stock' => $this->stock,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'toDate', $this->toDate])
            ->andFilterWhere(['like', 'fromDate', $this->fromDate]);

        return $dataProvider;
    }
}
