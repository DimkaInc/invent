<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Moving;
use app\models\Items;
use app\models\Status;
use app\models\Locations;
use app\models\Regions;

/**
 * MovingSearch represents the model behind the search form of `app\models\Moving`.
 */
class MovingSearch extends Moving
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'item_id', 'location_id', 'state_id'], 'integer'],
            [['date', 'comment', 'itemModel', 'statusName', 'locationName', 'regionName'], 'safe'],
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
        $query = Moving::find()
            ->select(Moving::tableName() . '.*, '
                . Items::tableName() .     '.model AS itemModel, '
                . Status::tableName() .    '.name AS statusName, '
                . Locations::tableName() . '.name AS locationName, '
                . Regions::tableName() .   '.name AS regionName' )
            ->joinWith(['items', 'status', 'locations', 'regions']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => SORT_ASC
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
            'id'          => $this->id,
            'date'        => $this->date,
            'item_id'     => $this->item_id,
            'location_id' => $this->location_id,
            'state_id'    => $this->state_id,
        ]);

        $query->andFilterWhere([ 'ilike', Items::tableName() .     '.model', $this->itemModel ]);
        $query->andFilterWhere([ 'ilike', Status::tableName() .    '.name',  $this->statusName ]);
        $query->andFilterWhere([ 'OR', [ 'ilike', Locations::tableName() . '.name',  $this->locationName ],
            [ 'ilike', Regions::tableName() .   '.name',  $this->locationName ]]);
        $query->andFilterWhere([ 'OR', [ 'ilike', Locations::tableName() . '.name',  $this->regionName ],
            [ 'ilike', Regions::tableName() .   '.name',  $this->regionName ]]);

        $query->andFilterWhere(['ilike', 'comment', $this->comment]);

        $dataProvider->sort->attributes[ 'itemModel' ] = [
            'asc'  => [ Items::tableName() . '.model' => SORT_ASC ],
            'desc' => [ Items::tableName() . '.model' => SORT_DESC ],
        ];

        $dataProvider->sort->attributes[ 'statusName' ] = [
            'asc'  => [ Status::tableName() . '.name' => SORT_ASC ],
            'desc' => [ Status::tableName() . '.name' => SORT_DESC ],
        ];
        $dataProvider->sort->attributes[ 'locationName' ] = [
            'asc'  => [ Locations::tableName() . '.name' => SORT_ASC ],
            'desc' => [ Locations::tableName() . '.name' => SORT_DESC ],
        ];
        $dataProvider->sort->attributes[ 'regionName' ] = [
            'asc'  => [ Regions::tableName() . '.name' => SORT_ASC ],
            'desc' => [ Regions::tableName() . '.name' => SORT_DESC ],
        ];

        return $dataProvider;
    }
}
