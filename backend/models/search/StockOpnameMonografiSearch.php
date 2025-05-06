<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\StockOpnameMonografi;

/**
 * StockOpnameMonografiSearch represents the model behind the search form of `backend\models\StockOpnameMonografi`.
 */
class StockOpnameMonografiSearch extends StockOpnameMonografi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_dokumen', 'tahun', 'jumlah_eksemplar', 'jumlah_scan'], 'integer'],
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
        $query = StockOpnameMonografi::find();

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
            'id_dokumen' => $this->id_dokumen,
            'tahun' => $this->tahun,
            'jumlah_eksemplar' => $this->jumlah_eksemplar,
            'jumlah_scan' => $this->jumlah_scan,
        ]);

        return $dataProvider;
    }
}
