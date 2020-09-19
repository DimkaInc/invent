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
$this->params[ 'breadcrumbs' ][] = $this->title;

?>
<div class="items-index">

    <h1><?= Html::encode($this->title) ?></h1>

<script language="JavaScript">
    function markToPrint(href)
    {
        url = href.attr("href");
        var ids = $("input[name='selection[]']");
        var res = '';
        for ( var i = 0; i < ids.length; i++)
        {
            if (ids[ i ][ "checked" ] == true)
            {
                res += "&id[]=" + ids[ i ][ "value" ];
            }
        }
        href.attr("href", url + res);
        return false;
    }
</script>

    <div class="row">
        <?php // Кнопки на форме ?>
        <div class="col-md-2"><?= Html::a(Yii::t('items', 'Create Items'),
            [ 'create' ],
            [ 'class' => 'btn btn-success' ]) ?></div>
        <div class="col-md-2"><?= Html::a(Yii::t('items', 'Import'),
            [ 'import' ],
            [ 'class' => 'btn btn-success',
                'style' => 'width: 100%;',
            ]) ?></div>
        <div class="col-md-2"><?= Html::a(Yii::t('items', 'Print Items'),
            Url::to([ 'print' ], 'http'),
            [ 'class' => 'btn btn-warning',
                'style' => 'width: 100%;',
                'target' => '_blank',
            ]) ?></div>
        <div class="col-md-2"><?= Html::a(Yii::t('items', 'Start checking'),
            [ 'start_checking' ],
            [ 'class' => 'btn btn-info',
            ]) ?></div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider, // Источник данных
        'filterModel' => $searchModel,   // Модель поиска
        'rowOptions' => function($model) // Функция окраски неинвентаризированных предметов/оборудования
            {
                if ($model->checked == false)     // Критерий проверки планируется
                {
                    return [ 'class' => 'danger' ]; // Отметка неинвентаризированной строки
                } else
                if ($model->statusName == 'Списано')
                {
                    return [ 'class' => 'info' ];
                }
            },
        'columns' => [
            // Нумератор строк
            [ 'class' => 'yii\grid\SerialColumn' ],

            // Инвентарный номер
            [ 'attribute' => 'invent',
                'value' => function ($data)
                    {
                        return showUrlUpdate($data->invent, $data);
                    },
                'format' => 'raw',
            ],

            // Серийный номер
            [ 'attribute' => 'serial',
                'value' => function ($data)
                    {
                        return showUrlUpdate($data->serial, $data);
                    },
                'format' => 'raw',
            ],

            // Модель
            [ 'attribute' => 'model',
                'value' => function ($data)
                    {
                        return showUrlUpdate($data->model, $data);
                    },
                'format' => 'raw',
            ],

            /* // Идентификатор записи
            [ 'attribute' => 'id',
                'value' => function ($data)
                    {
                        return showUrlUpdate($data->id, $data);
                    },
                'format' => 'raw',
            ], // */

            /* // Сетевое имя
            [ 'attribute' => 'name',
                'value' => function ($data)
                    {
                        return showUrlUpdate($data->name, $data);
                    },
                'format' => 'raw',
            ], // */

            // Название подразделения
            [ 'attribute' =>'regionName',
                'value' => function($data)
                    {
                        return showUrlUpdate($data->regionName .  ' (' . $data->locationName . ')', $data);
                    },
                'format' => 'raw',
            ],

            // Состояние
            [ 'attribute' => 'statusName',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'statusName',
                    ArrayHelper::merge(
                        [ '' => Yii::t('app', 'All statuses') ],
                        ArrayHelper::map(Status::find()->orderBy('name')->all(), 'name', 'name')),
                    [ 'class' => 'form-control' ],
                ),
                'value' => function ($data)
                    {
                        return showUrlUpdate($data->statusName, $data);
                    },
                'format' => 'raw',
            ],

            /* // Операционная система
            [ 'attribute' => 'os',
                'value' => function ($data)
                    {
                        return showUrlUpdate($data->os, $data->id);
                    },
                'format' => 'raw',
            ], // */

            /* // МАС - адрес
            [ 'attribute' => 'mac',
                'value' => function ($data)
                    {
                        return showUrlUpdate($data->mac, $data);
                    },
                'format' => 'raw',
            ], // */

            /* // Код товара
            [ 'attribute' => 'product',
                'value' => function ($data)
                    {
                        return showUrlUpdate($data->product, $data);
                    },
                'format' => 'raw',
            ], // */

            /* // Номер модели
            [ 'attribute' => 'modelnumber',
                'value' => function ($data)
                    {
                        return showUrlUpdate($data->modelnumber, $data);
                    },
                'format' => 'raw',
            ], // */

            /* // Примечания
            [ 'attribute' => 'comment',
                'value' => function ($data)
                    {
                        return showUrlUpdate($data->comment, $data);
                    },
                'format' => 'raw',
            ], // */

            // Чекбоксы для выбора предметов/оборудования для печати QR меток
            [ 'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function($model, $key, $index, $column)
                    {
                        return [ 'value' => $model->id, ];
                    },
            ],

            // Кнопки действий {view} {update} {delete} {print}
            [ 'class' => 'yii\grid\ActionColumn',
                'template' => '{delete} {print}',
                'buttons' => [
                    'print' => function ($url, $model, $key)
                        {
                            return Html::a('<span class="glyphicon glyphicon-print"></span>',
                                Url::to([ 'print', 'id[]' => $model->id ], 'http'),
                                [ 'target' => '_blank',
                                    'onclick' => 'markToPrint($(this));',
                                    'title' => Yii::t('items', 'Print selected labels'),
                                ]);
                        },
                ],
            ],
        ],
    ]); ?>

</div>
