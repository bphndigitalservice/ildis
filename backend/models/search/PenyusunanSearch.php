<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Rancangan;

/**
 * PenyusunanSearch represents the model behind the search form of `backend\models\Rancangan`.
 */
class PenyusunanSearch extends Rancangan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'jenis_rancangan_id', 'tahun', 'program_id', 'pemrakarsa_id', 'status_rancangan_id', 'is_publish', 'created_by', 'updated_by'], 'integer'],
            [['tahapan_rancangan', 'nama_rancangan', 'materi_muatan', 'keterangan', 'file_rancangan', 'file_naskah_akademik', 'tanggal_awal_publish', 'tanggal_akhir_publish', 'created_at', 'updated_at', 'peraturan_id'], 'safe'],
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
        $query = Rancangan::find()->where(['tahapan_rancangan'=>'Penyusunan']);

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
            'jenis_rancangan_id' => $this->jenis_rancangan_id,
            'tahun' => $this->tahun,
            'program_id' => $this->program_id,
            'pemrakarsa_id' => $this->pemrakarsa_id,
            'status_rancangan_id' => $this->status_rancangan_id,
            'is_publish' => $this->is_publish,
            'tanggal_awal_publish' => $this->tanggal_awal_publish,
            'tanggal_akhir_publish' => $this->tanggal_akhir_publish,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'tahapan_rancangan', $this->tahapan_rancangan])
            ->andFilterWhere(['like', 'nama_rancangan', $this->nama_rancangan])
            ->andFilterWhere(['like', 'materi_muatan', $this->materi_muatan])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan])
            ->andFilterWhere(['like', 'file_rancangan', $this->file_rancangan])
            ->andFilterWhere(['like', 'file_naskah_akademik', $this->file_naskah_akademik])
            ->andFilterWhere(['like', 'peraturan_id', $this->peraturan_id]);

        return $dataProvider;
    }
}
