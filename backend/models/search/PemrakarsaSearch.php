<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Pemrakarsa;

/**
 * PemrakarsaSearch represents the model behind the search form of `backend\models\Pemrakarsa`.
 */
class PemrakarsaSearch extends Pemrakarsa
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_publish', 'created_by', 'updated_by'], 'integer'],
            [['nama_pemrakarsa', 'singkatan', 'jenis', 'logo', 'created_at', 'updated_at'], 'safe'],
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
        $query = Pemrakarsa::find();

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
            'is_publish' => $this->is_publish,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'nama_pemrakarsa', $this->nama_pemrakarsa])
            ->andFilterWhere(['like', 'singkatan', $this->singkatan])
            ->andFilterWhere(['like', 'jenis', $this->jenis])
            ->andFilterWhere(['like', 'logo', $this->logo]);

        return $dataProvider;
    }
}
