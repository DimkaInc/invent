<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RegionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('regions', 'Regions');
$this->params['breadcrumbs'][] = ['label' => Yii::t('items',     'Items'),     'url' => ['items/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('locations', 'Locations'), 'url' => ['locations/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="regions-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('regions', 'Create Regions'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            // Идентификатор региона (неизменяемое)
            // 'id',

            // Название региона
            ['attribute' => 'name',
                'value'  => function ($data) {
                    return Html::a(Html::encode($data->name), Url::to(['update', 'id' => $data->id]));
                },
                'format' => 'raw',
            ],
 
            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
