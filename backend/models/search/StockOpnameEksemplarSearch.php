<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\StockOpnameEksemplar;

/**
 * StockOpnameEksemplarSearch represents the model behind the search form about `backend\models\StockOpnameEksemplar`.
 */
class StockOpnameEksemplarSearch extends StockOpnameEksemplar
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id',  'tahun', 'created_by', 'updated_by'], 'integer'],
            [['tanggal', 'created_at', 'updated_at', 'kode_eksemplar','dokumen_id'], 'safe'],
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
        $query = StockOpnameEksemplar::find()->joinWith('dokumen');

        // Reviews::find()->joinWith('FkStudent');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

       // $query

        $query->andFilterWhere([
            'id' => $this->id,
            'kode_eksemplar' => $this->kode_eksemplar,
            //'tanggal' => $this->tanggal,
           // 'dokumen_id' => $this->dokumen_id,
            'tahun' => $this->tahun,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query
        ->andFilterWhere(['like', 'dokumenJdih.nama', $this->dokumen_id]);


        return $dataProvider;
    }
}
