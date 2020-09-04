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

<script language="JavaScript">
    function aaaaa(href) {
        url = href.attr("href");
        var ids = $("input[name='selection[]']");
        var res = '';
        for ( var i = 0; i < ids.length; i++) {
            if (ids[i]["checked"] == true) {
                res += "&id[]=" + ids[i]["value"];
            }
        }
        href.attr("href", url + res);
        return false;
    }
</script>

    <p>
        <?= Html::a(Yii::t('items', 'Create Items'), ['create'], ['class' => 'btn btn-success']) ?> <?= Html::a(Yii::t('items', 'Print Items'), ['print'], ['class' => 'btn btn-warning', 'target' => '_blank']) ?>
    </p>

    <?php // Pjax::begin(); ?>
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

            // Чекбоксы для выбора предметов/оборудования для печати QR меток
            [
                'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function($model, $key, $index, $column) {
                    return ['value' => $model->id, ];
                },
            ],

            // Кнопки действий {view} {update} {delete}
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{delete} {print}',
                'buttons' => [
                    'print' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-print"></span>',
                            ['print', 'id[]' => $model->id],
                            [
                                'target' => '_blank',
                                'onclick' => 'aaaaa($(this));',
                                'title' => Yii::t('items', 'Print selected labels'),
                            ]);
                    },
                ], ],
        ],
    ]); ?>

    <?php // Pjax::end(); ?>

</div>
