<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Items;
use app\models\Status;
use app\models\Types;
use app\models\Locations;
use app\models\Regions;

/**
 * ItemsSearch represents the model behind the search form of `app\models\Items`.
 */
class ItemsSearch extends Items
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'state_id', 'type_id'], 'integer'],
            [['name', 'model', 'os', 'mac', 'serial', 'product', 'modelnumber', 'invent', 'date', 'comment', 'statusName', 'typeName', 'locationName', 'regionName'], 'safe'],
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
        $query = Items::find();
        $query->joinWith(['status']);
        $query->joinWith(['types']);
        $query->joinWith(['locations']);
//        $query->joinWith(['regions']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => SORT_ASC,
            ],
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
            'date' => $this->date,
        ])->andFilterWhere([
            'like', Status::tableName().'.name', $this->statusName
        ])->andFilterWhere([
            'like', Types::tableName().'.name', $this->typeName
        ])->andFilterWhere([
            'like', Locations::tableName().'.name', $this->locationName
//        ])->andFilterWhere([
//            'like', Regions::tableName().'.name', $this->regionName
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'model', $this->model])
            ->andFilterWhere(['ilike', 'os', $this->os])
            ->andFilterWhere(['ilike', 'mac', $this->mac])
            ->andFilterWhere(['ilike', 'serial', $this->serial])
            ->andFilterWhere(['ilike', 'product', $this->product])
            ->andFilterWhere(['ilike', 'modelnumber', $this->modelnumber])
            ->andFilterWhere(['ilike', 'invent', $this->invent])
            ->andFilterWhere(['ilike', 'comment', $this->comment]);

        $dataProvider->sort->attributes['statusName'] = [
            'asc' => [Status::tableName().'.name' => SORT_ASC],
            'desc' => [Status::tableName().'.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['typeName'] = [
            'asc' => [Types::tableName().'.name' => SORT_ASC],
            'desc' => [Types::tableName().'.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['locationName'] = [
            'asc' => [Locations::tableName().'.name' => SORT_ASC],
            'desc' => [Locations::tableName().'.name' => SORT_DESC],
        ];
//        $dataProvider->sort->attributes['regionName'] = [
//            'asc' => [Regions::tableName().'.name' => SORT_ASC],
//            'desc' => [Regions::tableName().'.name' => SORT_DESC],
//        ];
        
        return $dataProvider;
    }
}
