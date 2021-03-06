<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Items;
use app\models\Status;
use app\models\Types;
use app\models\Locations;
use app\models\Regions;
use app\models\Moving;

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
            [['id', 'model_id'], 'integer'],
            [['name', 'modelName', 'os', 'mac', 'serial', 'invent', 'date', 'comment', 'statusName', 'typeName', 'locationName', 'regionName'], 'safe'],
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

    public function noinvent($params)
    {

        $query = Moving::find()
            ->select('MAX(' . Moving::tableName() . '.id) AS mid');
        if (isset($params->region) && ($params->region != ''))
        {
            $query->joinWith([ 'locations' ])
                ->where([ 'region_id' => $params->region ]);
        }
        if (isset($params->location) && ($params->location != ''))
        {
            if (isset($params->region) && ($params->location != ''))
            {
                $query->andWhere(['location_id' => $params->location]);
            }
            else
            {
                $query->where(['location_id' => $params->location]);
            }
        }
        $query->distinct('item_id')->groupBy('item_id');

        $query = Items::find()
            ->select(Items::tableName() . '.*, ' .
                Locations::tableName() .  '.name AS locationName, ' .
                Models::tableName() .     '.name AS modelName, ' .
                Types::tableName() .      '.name AS typeName, ' .
                Regions::tableName() .    '.name AS regionName, ' .
                Status::tableName() .     '.name AS statusName ')
            ->joinWith([ 'types', 'moving', 'status', 'locations', 'regions', 'models', ])
            ->where([ 'in', Moving::tableName() . '.id', $query ])
            ->andWhere([ 'checked' => 0 ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => SORT_ASC,
            ],
        ]);

        $this->load($params);
        if (!$this->validate())
        {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'   => $this->id,
        ])->andFilterWhere([
            'ilike', Status::tableName() .    '.name', $this->statusName
        ])->andFilterWhere([
            'ilike', Models::tableName() .    '.name', $this->modelName
        ])->andFilterWhere([
            'ilike', Types::tableName() .     '.name', $this->typeName
        ])->andFilterWhere([ 'OR', [
            'ilike', Locations::tableName() . '.name', $this->regionName
        ], [
            'ilike', Regions::tableName() .   '.name', $this->regionName
        ]])->andFilterWhere([ 'OR', [
            'ilike', Locations::tableName() . '.name', $this->locationName
        ], [
            'ilike', Regions::tableName() .   '.name', $this->locationName
        ]]);

        $query->andFilterWhere(['ilike', 'name',        $this->name])
            ->andFilterWhere(  ['ilike', 'os',          $this->os])
            ->andFilterWhere(  ['ilike', 'mac',         $this->mac])
            ->andFilterWhere(  ['ilike', 'serial',      $this->serial])
            ->andFilterWhere(  ['ilike', 'invent',      $this->invent])
            ->andFilterWhere(  ['ilike', 'comment',     $this->comment]);


        $dataProvider->sort->attributes['modelName'] = [
            'asc'  => [ Models::tableName() . '.name' => SORT_ASC ],
            'desc' => [ Models::tableName() . '.name' => SORT_DESC ],
        ];
        $dataProvider->sort->attributes['statusName'] = [
            'asc'  => [ Status::tableName() . '.name' => SORT_ASC ],
            'desc' => [ Status::tableName() . '.name' => SORT_DESC ],
        ];
        $dataProvider->sort->attributes['typeName'] = [
            'asc'  => [ Types::tableName() . '.name' => SORT_ASC ],
            'desc' => [ Types::tableName() . '.name' => SORT_DESC ],
        ];
        $dataProvider->sort->attributes['locationName'] = [
            'asc'  => [ Locations::tableName() . '.name' => SORT_ASC ],
            'desc' => [ Locations::tableName() . '.name' => SORT_DESC ],
        ];
        $dataProvider->sort->attributes['regionName'] = [
            'asc'  => [ Regions::tableName() . '.name' => SORT_ASC ],
            'desc' => [ Regions::tableName() . '.name' => SORT_DESC ],
        ];
        return $dataProvider;
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
        // Особенность postgresql - нет first и last, потому последнее перемещение всегда имеет наибольший номер
        $subQuery = Moving::find()
                ->select('MAX(id) AS id')
                ->distinct('item_id')
                ->groupBy([ 'item_id' ]);

        $query = Items::find()
            ->select(Items::tableName() . '.*, ' .
                Locations::tableName() .  '.name AS locationName, ' .
                Models::tableName() .     '.name AS modelName, ' .
                Types::tableName() .      '.name AS typeName, ' .
                Regions::tableName() .    '.name AS regionName, ' .
                Status::tableName() .     '.name AS statusName ')
            ->joinWith([ 'types', 'moving', 'status', 'locations', 'regions', 'models', ])
            ->where([ 'in', Moving::tableName() . '.id', $subQuery ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->session['pageSize'] ?? 20,
            ],
        ]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => SORT_ASC,
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
            'id'   => $this->id,
        ])->andFilterWhere([
            'ilike', Status::tableName() .    '.name', $this->statusName
        ])->andFilterWhere([ 'OR', [
            'ilike', Models::tableName() .    '.name', $this->modelName
        ], [
            'ilike', Items::tableName() .     '.name', $this->modelName
        ]])->andFilterWhere([
            'ilike', Types::tableName() .     '.name', $this->typeName
        ])->andFilterWhere([ 'OR', [
            'ilike', Locations::tableName() . '.name', $this->regionName
        ], [
            'ilike', Regions::tableName() .   '.name', $this->regionName
        ]])->andFilterWhere([ 'OR', [
            'ilike', Locations::tableName() . '.name', $this->locationName
        ], [
            'ilike', Regions::tableName() .   '.name', $this->locationName
        ]]);

        $query->andFilterWhere(['ilike', 'name',        $this->name])
            ->andFilterWhere(  ['ilike', 'os',          $this->os])
            ->andFilterWhere(  ['ilike', 'mac',         $this->mac])
            ->andFilterWhere(  ['ilike', 'serial',      $this->serial])
            ->andFilterWhere(  ['ilike', 'invent',      $this->invent])
            ->andFilterWhere(  ['ilike', 'comment',     $this->comment]);

        $dataProvider->sort->attributes['modelName'] = [
            'asc'  => [Models::tableName() . '.name' => SORT_ASC],
            'desc' => [Models::tableName() . '.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['statusName'] = [
            'asc'  => [Status::tableName() . '.name' => SORT_ASC],
            'desc' => [Status::tableName() . '.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['typeName'] = [
            'asc'  => [Types::tableName() . '.name' => SORT_ASC],
            'desc' => [Types::tableName() . '.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['locationName'] = [
            'asc'  => [Locations::tableName() . '.name' => SORT_ASC],
            'desc' => [Locations::tableName() . '.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['regionName'] = [
            'asc'  => [Regions::tableName() . '.name' => SORT_ASC],
            'desc' => [Regions::tableName() . '.name' => SORT_DESC],
        ];

        return $dataProvider;
    }
}
