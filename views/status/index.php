<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Statuses');
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="status-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Status'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'id' => 'StatusTable',
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            [ 'class' => 'yii\grid\SerialColumn' ],

            [ 'attribute' => 'name',
                'value'   => function ($data)
                {
                    return showUrlUpdate($data->name, $data);
                },
                'format'  => 'raw',
            ],

            [ 'class' => 'yii\grid\ActionColumn', 'template' => '{delete}' ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
