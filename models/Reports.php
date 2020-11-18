<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

use app\models\Moving;
//use app\models\MovingsSearch;
use app\models\Items;
use app\models\Types;
use app\models\Status;
use app\models\Locations;
use app\models\Regions;


class Reports extends Moving
{
    public $modelname;
    public $type;
    public $status;
    public $region;
    public $location;
    public $netname;
    public $os;
    public $invent;
    public $serial;

    public function rules()
    {
        return [
            [[ 'id' ], 'integer' ],
            [[ 'date' ], 'date' ],
        ];
    }
    
    public function search($params)
    {
        // Особенность postgresql - нет first и last, потому последнее перемещение всегда имеет наибольший номер
        $subQuery = Moving::find()
                ->select('MAX(id) AS id')
                ->distinct('item_id')
                ->groupBy([ 'item_id' ]);

        $query = Moving::find()
            ->select(
                Moving::tableName() . '.*, '
                . Models::tableName() . '.name AS model, '
                . Locations::tableName() . '.name AS location, '
                . Regions::tableName() . '.name AS region'
            )
            ->joinWith([ 'items', 'models', 'types', 'status', 'regions', 'locations' ])
            ->where([ 'in', Moving::tableName() . '.id', $subQuery ])
            ->orderBy([
                Regions::tableName() . '.name' => SORT_ASC,
                Locations::tableName() . '.name' => SORT_ASC,
                Status::tableName() . '.name' => SORT_ASC,
                Models::tableName() . '.name' => SORT_ASC,
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}