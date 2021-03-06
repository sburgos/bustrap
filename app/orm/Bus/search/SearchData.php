<?php

namespace orm\bus\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use orm\bus\Data;

/**
 * SearchData represents the model behind the search form about `orm\bus\Data`.
 */
class SearchData extends Data
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
            [['id', 'idSmartNode'], 'integer'],
            [['moveDate'], 'safe'],
            [['velocity'], 'number'],
            [['isTraffic'], 'boolean'],
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
        $query = Data::find();
        if (!empty($with)) $query->with($with);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'idSmartNode' => $this->idSmartNode,
            'velocity' => $this->velocity,
            'isTraffic' => $this->isTraffic,
        ]);

        $query->andFilterWhere(['like', 'moveDate', $this->moveDate]);

        return $dataProvider;
    }
}
