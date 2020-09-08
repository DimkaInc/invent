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
class ItemsCheck extends ItemsSearch
{

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $dataProvider = parent::search($params);
        
        $query = $dataProvider->query;
        $query->andWhere([ Items::tableName() . '.checked' => false ]);
        
        return $dataProvider;
    }
}
