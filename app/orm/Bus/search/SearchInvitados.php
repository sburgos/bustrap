<?php

namespace orm\bus\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use orm\bus\Invitados;

/**
 * SearchInvitados represents the model behind the search form about `orm\bus\Invitados`.
 */
class SearchInvitados extends Invitados
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
            [['id', 'agendaId'], 'integer'],
            [['nombre', 'correo', 'skype'], 'safe'],
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
        $query = Invitados::find();
        if (!empty($with)) $query->with($with);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
            'agendaId' => $this->agendaId,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'correo', $this->correo])
            ->andFilterWhere(['like', 'skype', $this->skype]);

        return $dataProvider;
    }
}
