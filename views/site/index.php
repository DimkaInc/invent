<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

use yii\data\SqlDataProvider;

use app\models\Regions;
use app\models\Locations;
use app\models\User;

/* @var $this yii\web\View */



$this->title = Yii::t('app',Yii::$app->name);
?>
<div class="site-index">

    <h2><?= Yii::t('app', 'Number of equipment by regions') ?></h2>
    <?= GridView::widget([
        'id' => 'RegionsTable',
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            [ 'attribute' => 'icount',
                'contentOptions' => [
                    'class' => 'text-center',
                    'style' => 'max-width:100px; width:100px;',
                ],
                'headerOptions' => [
                    'class' => 'text-center',
                    'style' => 'max-width:100px; width:100px;',
                ],
            ],
            [ 'attribute' => 'ccount',
                'contentOptions' => [
                    'class' => 'text-center',
                    'style' => 'max-width:100px; width:100px;',
                ],
                'headerOptions' => [
                    'class' => 'text-center',
                    'style' => 'max-width:100px; width:100px;',
                ],
            ],
        ],
    ]);
    ?>

    <h2><?= Yii::t('app', 'Number of items by type') ?></h2>
    <?= GridView::widget([
        'id' => 'TypesTable',
        'dataProvider' => $dataProviderTypes,
        'columns' => [
            'name',
            [ 'attribute' => 'icount',
                'contentOptions' => [
                    'class' => 'text-center',
                    'style' => 'max-width:100px; width:100px;',
                ],
                'headerOptions' => [
                    'class' => 'text-center',
                    'style' => 'max-width:100px; width:100px;',
                ],
            ],
            [ 'attribute' => 'ccount',
                'contentOptions' => [
                    'class' => 'text-center',
                    'style' => 'max-width:100px; width:100px;',
                ],
                'headerOptions' => [
                    'class' => 'text-center',
                    'style' => 'max-width:100px; width:100px;',
                ],
            ],
//            'icount',
//            'ccount',
        ],
    ]);
    // Подключение кнопки если есть права
    if ( User::canPermission('takingInventory') )
    {
    ?>

    <div class="row">
        <div class="col-md-3"><?= Html::a(Yii::t('app', 'Inventory'), [ 'items/check' ], [ 'class' => 'btn btn-info' ]) ?></div>
    </div>
    <?php
    }
    ?>
</div>
