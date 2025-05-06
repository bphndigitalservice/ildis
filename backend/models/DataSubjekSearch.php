<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DataSubyek;

/**
 * DataSubjekSearch represents the model behind the search form of `backend\models\DataSubyek`.
 */
class DataSubjekSearch extends DataSubyek
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_dokumen', 'integrasi'], 'integer'],
            [['subyek', 'tipe_subyek', 'jenis_subyek', 'status', '_created_by', '_updated_by', 'created_at', 'updated_at'], 'safe'],
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
        $query = DataSubyek::find();
        //->select('subyek')->distinct();

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
            'id_dokumen' => $this->id_dokumen,
            'integrasi' => $this->integrasi,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'subyek', $this->subyek])
            ->andFilterWhere(['like', 'tipe_subyek', $this->tipe_subyek])
            ->andFilterWhere(['like', 'jenis_subyek', $this->jenis_subyek])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', '_created_by', $this->_created_by])
            ->andFilterWhere(['like', '_updated_by', $this->_updated_by]);

        return $dataProvider;
    }
}
