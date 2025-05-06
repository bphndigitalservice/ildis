<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\JenisRancangan;

/**
 * JenisRancanganSearch represents the model behind the search form of `backend\models\JenisRancangan`.
 */
class JenisRancanganSearch extends JenisRancangan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'urutan', 'created_by', 'updated_by'], 'integer'],
            [['nama_rancangan', 'singkatan', 'created_at', 'updated_at'], 'safe'],
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
        $query = JenisRancangan::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_ASC]]
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
            'urutan' => $this->urutan,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'nama_rancangan', $this->nama_rancangan])
            ->andFilterWhere(['like', 'singkatan', $this->singkatan]);

        return $dataProvider;
    }
}
