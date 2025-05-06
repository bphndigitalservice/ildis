<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Member;

/**
 * MemberSearch represents the model behind the search form of `backend\models\Member`.
 */
class Member2Search extends Member
{
    /**
     * @inheritdoc
     */
    public $globalsearch;

    public function rules()
    {
        return [
            [['id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['globalsearch', 'username', 'password_hash', 'member_name', 'gender', 'birth_date', 'member_type_id', 'member_address', 'member_email', 'postal_code', 'personal_id_number', 'inst_name', 'member_image', 'member_since_date', 'register_date', 'expire_date', 'phone_number', 'fax_number', 'member_notes', 'created_at', 'updated_at', 'auth_key', 'password_reset_token'], 'safe'],
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
        $query = Member::find();

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


        $query->orFilterWhere(['like', 'username', $this->globalsearch])

            ->orFilterWhere(['like', 'member_name', $this->globalsearch])
->orFilterWhere(['like', 'personal_id_number', $this->globalsearch])
            ->orFilterWhere(['like', 'member_email', $this->globalsearch]);

        return $dataProvider;
    }
}
