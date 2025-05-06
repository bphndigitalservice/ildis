<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\MemberType;

/**
 * MemberTypeSearch represents the model behind the search form of `backend\models\MemberType`.
 */
class MemberTypeSearch extends MemberType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'loan_limit', 'loan_periode', 'enable_reserve', 'reserve_limit', 'member_periode', 'reborrow_limit', 'fine_each_day', 'grace_periode', 'created_by', 'updated_by'], 'integer'],
            [['member_type_name', 'input_date', 'last_update', 'id_tipe_koleksi', 'id_tipe_gmd', 'created_at', 'updated_at'], 'safe'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = MemberType::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
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
            'loan_limit' => $this->loan_limit,
            'loan_periode' => $this->loan_periode,
            'enable_reserve' => $this->enable_reserve,
            'reserve_limit' => $this->reserve_limit,
            'member_periode' => $this->member_periode,
            'reborrow_limit' => $this->reborrow_limit,
            'fine_each_day' => $this->fine_each_day,
            'grace_periode' => $this->grace_periode,
            'input_date' => $this->input_date,
            'last_update' => $this->last_update,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'member_type_name', $this->member_type_name])
        ->andFilterWhere(['like', 'id_tipe_koleksi', $this->id_tipe_koleksi])
        ->andFilterWhere(['like', 'id_tipe_gmd', $this->id_tipe_gmd]);

        return $dataProvider;
    }
}
