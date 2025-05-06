<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\FrontendConfig;

/**
 * BeritaSearch represents the model behind the search form of `backend\models\Berita`.
 */
class FrontendConfigSearch extends FrontendConfig
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nama_konfig', 'is_konfig', 'jenis', 'default'], 'safe'],
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
        $query = FrontendConfig::find();

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
            'nama_kofig' => $this->nama_konfig,
            'jenis' => $this->jenis,
            
        ]);

        $query->andFilterWhere(['like', 'default', $this->default])
        ->andFilterWhere(['like', 'isi_konfig', $this->isi_konfig]);


        return $dataProvider;
    }
}
