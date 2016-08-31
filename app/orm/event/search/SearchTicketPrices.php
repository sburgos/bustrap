<?php

namespace orm\event\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use orm\event\TicketPrices;

/**
 * SearchTicketPrices represents the model behind the search form about `orm\event\TicketPrices`.
 */
class SearchTicketPrices extends TicketPrices
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
            [['id', 'ticketId', 'priceId', 'shareId'], 'integer'],
            [['priceName', 'shareName', 'currency'], 'safe'],
            [['amount'], 'number'],
            [['paid'], 'boolean'],
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
        $query = TicketPrices::find();
        if (!empty($with)) $query->with($with);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ticketId' => $this->ticketId,
            'priceId' => $this->priceId,
            'shareId' => $this->shareId,
            'amount' => $this->amount,
            'paid' => $this->paid,
        ]);

        $query->andFilterWhere(['like', 'priceName', $this->priceName])
            ->andFilterWhere(['like', 'shareName', $this->shareName])
            ->andFilterWhere(['like', 'currency', $this->currency]);

        return $dataProvider;
    }
}
