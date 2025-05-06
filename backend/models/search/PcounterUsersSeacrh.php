<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PcounterUsers;

/**
 * PcounterUsersSeacrh represents the model behind the search form of `backend\models\PcounterUsers`.
 */
class PcounterUsersSeacrh extends PcounterUsers
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_time'], 'integer'],
            [['user_ip', 'creation_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PcounterUsers::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_time' => $this->user_time,
            'creation_date' => $this->creation_date,
        ]);

        $query->andFilterWhere(['like', 'user_ip', $this->user_ip]);

        return $dataProvider;
    }
}
