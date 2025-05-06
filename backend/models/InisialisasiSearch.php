<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Inisialisasi;

/**
 * InisialisasiSearch represents the model behind the search form of `backend\models\Inisialisasi`.
 */
class InisialisasiSearch extends Inisialisasi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'lokasi', 'status'], 'integer'],
            [['nama_inisialisasi', 'gmd', 'tipe_koleksi', 'lokasi_rak', 'klasifikasi', 'tanggal_dimulai', 'tanggal_berakhir', 'pelaksana'], 'safe'],
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
        $query = Inisialisasi::find();

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
            'lokasi' => $this->lokasi,
            'tanggal_dimulai' => $this->tanggal_dimulai,
            'tanggal_berakhir' => $this->tanggal_berakhir,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'nama_inisialisasi', $this->nama_inisialisasi])
            ->andFilterWhere(['like', 'gmd', $this->gmd])
            ->andFilterWhere(['like', 'tipe_koleksi', $this->tipe_koleksi])
            ->andFilterWhere(['like', 'lokasi_rak', $this->lokasi_rak])
            ->andFilterWhere(['like', 'klasifikasi', $this->klasifikasi])
            ->andFilterWhere(['like', 'pelaksana', $this->pelaksana]);

        return $dataProvider;
    }
}
