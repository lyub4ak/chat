<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Message;

/**
 * MessageSearch represents the model behind the search form of `common\models\Message`.
 */
class MessageSearch extends Message
{
    /**
     * Name of user.
     *
     * @var string
     */
    public $userName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_banned', 'created_by_id', 'updated_by_id', 'created_at', 'updated_at'], 'integer'],
            [['text', 'userName'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Message::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $sortAttributes = $dataProvider->getSort()->attributes;
        // adds sorting by user name
        $sortAttributes['userName'] = [
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC],
            'label' => 'User Name'
        ];
        $dataProvider->setSort(['attributes' => $sortAttributes]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            $query->joinWith(['user']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_banned' => $this->is_banned,
            'created_by_id' => $this->created_by_id,
            'updated_by_id' => $this->updated_by_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text]);

        // filter by user name
        $query->joinWith(['user' => function ($q) {
            $q->where('user.username LIKE "%' . $this->userName . '%"');
        }]);

        return $dataProvider;
    }
}
