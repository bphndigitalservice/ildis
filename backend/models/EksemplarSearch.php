<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Eksemplar;

/**
 * EksemplarSearch represents the model behind the search form of `backend\models\Eksemplar`.
 */
class EksemplarSearch extends Eksemplar
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'harga', 'created_by', 'updated_by'], 'integer'],
            [['id_dokumen', 'kode_eksemplar', 'no_panggil', 'kode_inventaris', 'id_lokasi', 'lokasi_rak', 'tipe_lokasi', 'status_eksemplar', 'nomor_pemesanan', 'tgl_pemesanan', 'tgl_penerimaan', 'agen', 'sumber_perolehan', 'faktur', 'tgl_faktur', 'created_at', 'updated_at'], 'safe'],
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

    public function get_eksemplarByIds($ids)
    {
        $query = Eksemplar::find()->where(['IN', 'id_dokumen', $ids]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        return $dataProvider;
    }

    public function search($params)
    {
        $query = Eksemplar::find();

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

        $query->joinWith('monografi');
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tgl_pemesanan' => $this->tgl_pemesanan,
            'tgl_penerimaan' => $this->tgl_penerimaan,
            'tgl_faktur' => $this->tgl_faktur,
            'harga' => $this->harga,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'judul', $this->id_dokumen])
            ->andFilterWhere(['like', 'kode_eksemplar', $this->kode_eksemplar])
            ->andFilterWhere(['like', 'no_panggil', $this->no_panggil])
            ->andFilterWhere(['like', 'kode_inventaris', $this->kode_inventaris])
            ->andFilterWhere(['like', 'id_lokasi', $this->id_lokasi])
            ->andFilterWhere(['like', 'lokasi_rak', $this->lokasi_rak])
            ->andFilterWhere(['like', 'tipe_lokasi', $this->tipe_lokasi])
            ->andFilterWhere(['like', 'status_eksemplar', $this->status_eksemplar])
            ->andFilterWhere(['like', 'nomor_pemesanan', $this->nomor_pemesanan])
            ->andFilterWhere(['like', 'agen', $this->agen])
            ->andFilterWhere(['like', 'sumber_perolehan', $this->sumber_perolehan])
            ->andFilterWhere(['like', 'faktur', $this->faktur]);

        return $dataProvider;
    }
}
