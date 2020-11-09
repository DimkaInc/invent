<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * Эта модель используется для формирования сводныз данных на стартовой странице,
 * исполшьзуя данные из связанных аблиц ({%items}), {{%types}}, {{%locations}}, {{%regions}}/
 *
 * @property ActiveDataProvider|null regionsDataProvider Сводная таблица, содержащая количество оборудования по регионам
 * @property ActiveDataProvider|null typesDataProvider   Сводная таблица, содержащая количество оборудования по типам
 */

class Site extends \yii\data\ActiveDataProvider
{

    public static function regionsDataProvider()
    {

        $subQuery = Moving::find()
                ->select('MAX(id) AS id')
                ->distinct('item_id')
                ->groupBy(['item_id']);

        $query = Regions::find()
            ->select(Regions::tableName() . '.name, count(' . Items::tableName() . '.id) AS icount, count(c.tid) AS ccount')
            ->joinWith(['locations', 'moving', 'items'])
            ->leftJoin(['c' => Regions::find()
                    ->select(Regions::tableName() . '.id, ' . Items::tableName() . '.id AS tid')
                    ->joinWith(['locations', 'moving', 'items'])
                    ->where(['in', Moving::tableName() . '.id', $subQuery])
                    ->andWhere(Items::tableName() . '.checked = true')
                ], Regions::tableName() . '.id = c.id AND ' . Items::tableName() . '.id = c.tid')
            ->groupBy(Regions::tableName() . '.id')
            ->where(['in', Moving::tableName() . '.id', $subQuery]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'defaultOrder' => [
                'name' => SORT_ASC,
            ],
        ]);

        return $dataProvider;
    }

    public static function typesDataProvider()
    {

        $query = Types::find()
            ->select(Types::tableName() . '.name, count(' . Items::tableName() . '.id) AS icount, count( c.tid ) AS ccount')
            ->joinWith([ 'items', 'models' ])
            ->leftJoin(['c' => Items::find()
                    ->select('id AS tid')
                    ->where(['checked' => true ])
                ], Items::tableName() . '.id = c.tid')
            ->groupBy(Types::tableName() . '.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setSort([
            'defaultOrder' => [
                'name' => SORT_ASC,
            ],
        ]);
        return $dataProvider;
    }
}
