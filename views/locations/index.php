<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

use app\models\Regions;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LocationsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('locations', 'Locations');
$this->params['breadcrumbs'][] = ['label' => Yii::t('items', 'Items'), 'url' => ['items/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="locations-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('locations', 'Create Locations'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',

            // Название места размещения
            ['attribute' => 'name',
                'value' => function ($data) {
                    return Html::a(Html::encode($data->name), Url::to(['update', 'id' => $data->id]));
                },
                'format' => 'raw',
            ],

            // Название региона
            ['attribute' => 'regionName',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'regionName',
                    ArrayHelper::merge(['' => Yii::t('locations', 'All regions')], ArrayHelper::map(Regions::find()->orderBy('name')->all(), 'name', 'name')),
                    ['class' => 'form-control' ],
                ),
                'value' => function ($data) {
                    return Html::a(Html::encode($data->regions->name), Url::to(['update', 'id' => $data->id]));
                },
                'format' => 'raw',
            ],

            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
