<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\MovingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('moving', 'Movings');
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="moving-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('moving', 'Create Moving'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'id' => 'MovingTable',
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            [ 'class' => 'yii\grid\SerialColumn' ],

//            'id',
            [ 'attribute' => 'date',
                'value' => function($data)
                {
                    return showUrlUpdate($data->date, $data);
                },
                'format' => 'raw',
            ],
//            'item_id',
            [ 'attribute' => 'itemModel',
                'value' => function($data)
                {
                    return showUrlUpdate($data->items->invent . ' (' .$data->items->model . ')', $data);
                },
                'format' => 'raw',
            ],
//            'location_id',
            [ 'attribute' => 'locationName',
                'value' => function($data)
                {
                    return showUrlUpdate($data->locations->name, $data);
                },
                'format' => 'raw',
            ],
//            'state_id',
            [ 'attribute' => 'statusName',
                'value' => function($data)
                {
                    return showUrlUpdate($data->status->name, $data);
                },
                'format' => 'raw',
            ],
//            'comment:ntext',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
