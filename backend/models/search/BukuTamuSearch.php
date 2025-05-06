<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\BukuTamu;

/**
 * BukuTamuSearch represents the model behind the search form of `backend\models\BukuTamu`.
 */
class BukuTamuSearch extends BukuTamu
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by'], 'integer'],
            [['nama_tamu', 'institusi_tamu', 'tanggal_masuk', 'anggota', 'no_telp', 'email', 'keperluan', 'created_at', 'updated_at'], 'safe'],
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
        $query = BukuTamu::find();

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
            'tanggal_masuk' => $this->tanggal_masuk,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'nama_tamu', $this->nama_tamu])
            ->andFilterWhere(['like', 'institusi_tamu', $this->institusi_tamu])
            ->andFilterWhere(['like', 'anggota', $this->anggota])
            ->andFilterWhere(['like', 'no_telp', $this->no_telp])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'keperluan', $this->keperluan]);

        return $dataProvider;
    }
}
