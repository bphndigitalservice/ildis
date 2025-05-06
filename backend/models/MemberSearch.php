<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Member;

/**
 * MemberSearch represents the model behind the search form of `backend\models\Member`.
 */
class MemberSearch extends Member
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['username', 'password_hash', 'member_name', 'gender', 'birth_date', 'member_type_id', 'member_address', 'member_email', 'postal_code', 'personal_id_number', 'inst_name', 'member_image', 'member_since_date', 'register_date', 'expire_date', 'phone_number', 'fax_number', 'member_notes', 'created_at', 'updated_at', 'auth_key', 'password_reset_token'], 'safe'],
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
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'birth_date' => $this->birth_date,
            'member_since_date' => $this->member_since_date,
            'register_date' => $this->register_date,
            'expire_date' => $this->expire_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'member_name', $this->member_name])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'member_type_id', $this->member_type_id])
            ->andFilterWhere(['like', 'member_address', $this->member_address])
            ->andFilterWhere(['like', 'member_email', $this->member_email])
            ->andFilterWhere(['like', 'postal_code', $this->postal_code])
            ->andFilterWhere(['like', 'personal_id_number', $this->personal_id_number])
            ->andFilterWhere(['like', 'inst_name', $this->inst_name])
            ->andFilterWhere(['like', 'member_image', $this->member_image])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'fax_number', $this->fax_number])
            ->andFilterWhere(['like', 'member_notes', $this->member_notes])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token]);

        return $dataProvider;
    }
}
