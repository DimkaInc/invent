<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

//use yii\db\Query;
//use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

use app\models\Regions;
use app\models\Locations;

/* @var $this yii\web\View */



//    $query = Regions::find()
//        ->joinWith(['locations as l'])
//        ->where(['locations.region_id' => 'regions.id'])
//        ->select('regions.*, l.name as lname');
//    $query = (new Query())
//        ->select(' regions.*, l.name as lname')
//        ->from('regions')
//        ->rightJoin(['locations as l on l.region_id = id']);

//    $dataProvider = new ActiveDataProvider([
//        'query' => $query,
//    ]);

//    $dataProvider->setSort([
//        'defaultOrder' => [
//            'name' => SORT_ASC,
//        ],
//    ]);

    $count = Yii::$app->db->createCommand('
        SELECT COUNT(*) FROM regions
    ')->queryScalar();

    $dataProvider = new SqlDataProvider([
        'sql' => "
            SELECT
                r.name AS rname,
                COUNT(i.id) AS icount
            FROM regions AS r
                LEFT JOIN locations AS l
                    ON l.region_id = r.id
                LEFT JOIN items AS i
                    ON i.location_id = l.id
            GROUP BY
                rname
            ORDER BY
                rname
        ",
        'totalCount' => $count,
        'pagination' => [
            'pageSize' => 10,
        ],
        'sort' => [
            'attributes' => [
                'rname',
                'icount',
            ],
        ],
    ]);

    $countg = Yii::$app->db->createCommand('
        SELECT COUNT(*) FROM types
    ')->queryScalar();

    $dataProviderg = new SqlDataProvider([
        'sql' => "
            SELECT
                t.name AS tname,
                COUNT(i.id) AS icount
            FROM types AS t
                LEFT JOIN items AS i
                    ON i.type_id = t.id
            GROUP BY
                tname
            ORDER BY
                tname
        ",
        'totalCount' => $countg,
        'pagination' => [
            'pageSize' => 20,
        ],
        'sort' => [
            'attributes' => [
                'tname',
                'icount',
            ],
        ],
    ]);

$this->title = Yii::t('app','Inventory');
?>
<div class="site-index">
    <h2>Количество оборудования по подразделениям</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            [
                'attribute' => 'rname',
                'label' => Yii::t('regions', 'Regions'),
                'value' => 'rname'
            ],
            [
                'attribute' => 'icount',
                'label' => Yii::t('regions', 'Total items count'),
                'value' => 'icount',
            ],
        ],
    ]);
    ?>

    <h2>Количество оборудования по типам</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProviderg,
        'columns' => [

            [
                'attribute' => 'tname',
                'label' => Yii::t('types', 'Types'),
                'value' => 'tname'
            ],
            [
                'attribute' => 'icount',
                'label' => Yii::t('types', 'Total items count'),
                'value' => 'icount',
            ],
        ],
    ]);
    ?>

</div>
