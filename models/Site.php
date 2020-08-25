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
        $query = Regions::find()
            ->select(Regions::tableName() . '.name, count(' . Items::tableName() . '.id) AS icount')
            ->joinWith(['locations', 'items'])
            ->groupBy(Regions::tableName() . '.id');

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
            ->select(Types::tableName() . '.name, count(' . Items::tableName() . '.id) AS icount')
            ->joinWith('items')
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
