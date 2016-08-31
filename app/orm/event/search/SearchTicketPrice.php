<?php

namespace orm\event\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use orm\event\TicketPrice;

/**
 * SearchTicketPrice represents the model behind the search form about `orm\event\TicketPrice`.
 */
class SearchTicketPrice extends TicketPrice
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
            [['ticketId', 'shares'], 'integer'],
            [['name', 'currency'], 'safe'],
            [['amount'], 'number'],
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
        $query = TicketPrice::find();
        if (!empty($with)) $query->with($with);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ticketId' => $this->ticketId,
            'amount' => $this->amount,
            'shares' => $this->shares,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'currency', $this->currency]);

        return $dataProvider;
    }
}
