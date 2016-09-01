<?php

namespace orm\bus\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use orm\bus\Bus;

/**
 * SearchBus represents the model behind the search form about `orm\bus\Bus`.
 */
class SearchBus extends Bus
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
            [['id', 'idLine'], 'integer'],
            [['name', 'busImage', 'extraInfo'], 'safe'],
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
        $query = Bus::find();
        if (!empty($with)) $query->with($with);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idLine' => $this->idLine,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'busImage', $this->busImage])
            ->andFilterWhere(['like', 'extraInfo', $this->extraInfo]);

        return $dataProvider;
    }
}
