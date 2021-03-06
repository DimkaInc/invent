<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Locations;
use app\models\Regions;
use app\models\Moving;
use app\models\Items;

/**
 * LocationsSearch represents the model behind the search form of `app\models\Locations`.
 */
class LocationsSearch extends Locations
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'region_id'], 'integer'],
            [['name', 'regionName'], 'safe'],
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
     * Список мест/размещений, где не проинвентаризированы предметы/оборудование
     */
     public static function noinvent()
     {
        $query = Locations::find()
            ->innerJoin([ 'm' => Moving::find()
                ->distinct()
                ->select('location_id')
                ->innerJoin([ 'm' => Moving::find()
                    ->select('MAX(' . Moving::tableName() . '.id) AS mid')
                    ->joinWith([ 'items' ])
                    ->where([ Items::tableName() . '.checked' => 0 ])
                    ->groupBy([ 'item_id' ])
                ], 'id = m.mid')
            ], Locations::tableName() . '.id = m.location_id');
        return $query;
     }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Locations::find()
            ->select(Locations::tableName() . '.*, ' .
                Regions::tableName() . '.name AS regionName')
            ->joinWith(['regions']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'name' => SORT_ASC,
            ],
        ]);

        $this->load($params);

        if (!$this->validate())
        {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'        => $this->id,
            'region_id' => $this->region_id,
        ])->andFilterWhere([
            'like', Regions::tableName() . '.name', $this->regionName,
        ]);

        $dataProvider->sort->attributes['regionName'] = [
            'asc'  => [Regions::tableName() . '.name' => SORT_ASC],
            'desc' => [Regions::tableName() . '.name' => SORT_DESC],
        ];

        $query->andFilterWhere(['ilike', Locations::tableName() . '.name', $this->name]);

        return $dataProvider;
    }
}
