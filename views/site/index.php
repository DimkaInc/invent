<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

use yii\data\SqlDataProvider;

use app\models\Regions;
use app\models\Locations;

/* @var $this yii\web\View */



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
