<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Circulation;

/**
 * CirculationSearch represents the model behind the search form of `backend\models\Circulation`.
 */
class Circulation2Search extends Circulation
{
    /**
     * @inheritdoc
     */
    public $globalsearch;


    public function rules()
    {
        return [
            [['id', 'member_id', 'document_id', 'item_id', '_created_by', '_updated_by'], 'integer'],
            [['member', 'globalsearch', 'title', 'item_code', 'tanggal_pinjam', 'tanggal_kembali', 'status', 'denda', 'created_at', 'updated_at'], 'safe'],
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
        $query = Circulation::find()->where(['status' => 1]);

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


        $query->orFilterWhere(['like', 'member_id', $this->globalsearch])
            ->orFilterWhere(['like', 'item_id', $this->globalsearch])
            ->orFilterWhere(['like', 'item_code', $this->globalsearch]);


        return $dataProvider;
    }
}
