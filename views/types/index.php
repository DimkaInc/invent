<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TypesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('types', 'Types');
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('items', 'Items'), 'url' => [ 'items/index' ]];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="types-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('types', 'Create Types'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'id' => 'TypesTable',
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            [ 'class' => 'yii\grid\SerialColumn' ],

            // Наименование типа оборудования
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
