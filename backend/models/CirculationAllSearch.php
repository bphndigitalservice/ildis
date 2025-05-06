<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Circulation;

/**
 * CirculationSearch represents the model behind the search form of `backend\models\Circulation`.
 */
class CirculationAllSearch extends Circulation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'document_id', 'item_id', 'created_by', 'updated_by'], 'integer'],
            [['member', 'title', 'item_code', 'tanggal_pinjam', 'tanggal_kembali', 'status', 'denda', 'created_at', 'updated_at'], 'safe'],
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
        $query = Circulation::find();


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
            'member_id' => $this->member_id,
            'document_id' => $this->document_id,
            'item_id' => $this->item_id,
            'tanggal_pinjam' => $this->tanggal_pinjam,
            'tanggal_kembali' => $this->tanggal_kembali,

        ]);

        $query->andFilterWhere(['like', 'member', $this->member])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'item_code', $this->item_code])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'denda', $this->denda]);

        return $dataProvider;
    }
}
