<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Models;
use app\models\Types;

/**
 * ModelsSearch represents the model behind the search form of `app\models\Models`.
 */
class ModelsSearch extends Models
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type_id'], 'integer'],
            [['name', 'modelnum', 'product', 'typeName'], 'safe'],
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
        $query = Models::find()
            ->select(Models::tableName().'.*, ' . Types::tableName() . 'name AS typeName')
            ->joinWith([ 'types' ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['typeName'] = [
            'asc'  => [ Types::tableName() . '.name' => SORT_ASC ],
            'desc' => [ Types::tableName() . '.name' => SORT_DESC ],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type_id' => $this->type_id,
        ]);

        $query->andFilterWhere([ 'ilike', 'name', $this->name ])
            ->andFilterWhere([ 'ilike', 'modelnum', $this->modelnum ])
            ->andFilterWhere([ 'ilike', 'product', $this->product ])
            ->andFilterWhere([ 'ilike', 'typeName', $this->typeName ]);

        return $dataProvider;
    }
}
