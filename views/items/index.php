<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use app\models\Status;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('items', 'Items');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="items-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('items', 'Create Items'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // Нумератор строк
            ['class' => 'yii\grid\SerialColumn'],

            // Инвентарный номер
            ['attribute' => 'invent',
                'value' => function ($data) {
                    return showUrlUpdate($data->invent, $data);
                },
                'format' => 'raw',
            ],

            // Серийный номер
            ['attribute' => 'serial',
                'value' => function ($data) {
                    return showUrlUpdate($data->serial, $data);
                },
                'format' => 'raw',
            ],

            // Модель
            ['attribute' => 'model',
                'value' => function ($data) {
                    return showUrlUpdate($data->model, $data);
                },
                'format' => 'raw',
            ],

            /* // Идентификатор записи
            ['attribute' => 'id',
                'value' => function ($data) {
                    return showUrlUpdate($data->id, $data);
                },
                'format' => 'raw',
            ], // */

            /* // Сетевое имя
            ['attribute' => 'name',
                'value' => function ($data) {
                    return showUrlUpdate($data->name, $data);
                },
                'format' => 'raw',
            ], // */

            // Название подразделения
            ['attribute' =>'regionName',
                'value' => function($data) {
                    return showUrlUpdate($data->regionName .  ' (' . $data->locationName . ')', $data);
                },
                'format' => 'raw',
            ],

            // Состояние
            ['attribute' => 'statusName',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'statusName',
                    ArrayHelper::merge(['' => Yii::t('app', 'All statuses')], ArrayHelper::map(Status::find()->orderBy('name')->all(), 'name', 'name')),
                    ['class' => 'form-control' ],
                ),
                'value' => function ($data) {
                    return showUrlUpdate($data->statusName, $data);
                },
                'format' => 'raw',
            ],

            /* // Операционная система
            ['attribute' => 'os',
                'value' => function ($data) {
                    return showUrlUpdate($data->os, $data->id);
                },
                'format' => 'raw',
            ], // */

            /* // МАС - адрес
            ['attribute' => 'mac',
                'value' => function ($data) {
                    return showUrlUpdate($data->mac, $data);
                },
                'format' => 'raw',
            ], // */

            /* // Код товара
            ['attribute' => 'product',
                'value' => function ($data) {
                    return showUrlUpdate($data->product, $data);
                },
                'format' => 'raw',
            ], // */

            /* // Номер модели
            ['attribute' => 'modelnumber',
                'value' => function ($data) {
                    return showUrlUpdate($data->modelnumber, $data);
                },
                'format' => 'raw',
            ], // */

            /* // Примечания
            ['attribute' => 'comment',
                'value' => function ($data) {
                    return showUrlUpdate($data->comment, $data);
                },
                'format' => 'raw',
            ], // */

            // Кнопки действий {view} {update} {delete}
            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}' ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
