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
    <h2><?= Yii::t('app', 'Number of equipment by regions') ?></h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'name',
            [
                'attribute' => 'icount',
                'label' => Yii::t('regions', 'Total items count'),
                'value' => 'icount',
            ],
        ],
    ]);
    ?>

    <h2><?= Yii::t('app', 'Number of items by type') ?></h2>
    <?= GridView::widget([
        'dataProvider' => $dataProviderTypes,
        'columns' => [
            'name',
            [
                'attribute' => 'icount',
                'label' => Yii::t('types', 'Total items count'),
                'value' => 'icount',
            ],
        ],
    ]);
    ?>

</div>
