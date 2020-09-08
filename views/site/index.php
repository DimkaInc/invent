<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

use yii\data\SqlDataProvider;

use app\models\Regions;
use app\models\Locations;

/* @var $this yii\web\View */



$this->title = Yii::t('app',Yii::$app->name);
?>
<div class="site-index">

    <h2><?= Yii::t('app', 'Number of equipment by regions') ?></h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'name',
            'icount',
            'ccount',
        ],
    ]);
    ?>

    <h2><?= Yii::t('app', 'Number of items by type') ?></h2>
    <?= GridView::widget([
        'dataProvider' => $dataProviderTypes,
        'columns' => [
            'name',
            'icount',
            'ccount',
        ],
    ]);
    ?>

    <div>
        <?= Html::a(Yii::t('app', 'Invent'), [ 'items/check' ], [ 'class' => 'btn btn-info' ]) ?>
    </div>

</div>
