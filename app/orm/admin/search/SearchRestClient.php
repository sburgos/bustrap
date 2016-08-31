<?php

namespace orm\admin\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use orm\admin\RestClient;

/**
 * SearchRestClient represents the model behind the search form about `orm\admin\RestClient`.
 */
class SearchRestClient extends RestClient
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
            [['id', 'secretToken', 'displayName', 'platform', 'version', 'ownerName', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['sendSiftFingerprint', 'active', 'allowCreditCards', 'allowStoredCreditCards', 'allowPaypal'], 'boolean'],
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
        $query = RestClient::find();
        if (!empty($with)) $query->with($with);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'sendSiftFingerprint' => $this->sendSiftFingerprint,
            'active' => $this->active,
            'allowCreditCards' => $this->allowCreditCards,
            'allowStoredCreditCards' => $this->allowStoredCreditCards,
            'allowPaypal' => $this->allowPaypal,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'secretToken', $this->secretToken])
            ->andFilterWhere(['like', 'displayName', $this->displayName])
            ->andFilterWhere(['like', 'platform', $this->platform])
            ->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'ownerName', $this->ownerName]);

        return $dataProvider;
    }
}
