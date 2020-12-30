<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use app\models\Status;
use app\models\User;
use app\widgets\MyLinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('items', 'Items');
$this->params[ 'breadcrumbs' ][] = $this->title;

// Формирование колонок и кнопок
// номер строки
$columns = [[ 'class' => 'yii\grid\SerialColumn' ]];
// Кнопки действий {view} {update} {delete} {print}
$template = '';
$buttons = [];

$pageSize = Yii::$app->session['pageSize'] ?? 20;

if (User::canPermission('createRecord'))
{
    $template .= ' {copy}';
    $buttons [ 'copy' ] = function ($url, $model, $key)
        {
            return Html::a('<span class="glyphicon glyphicon-share"></span>',
                Url::to([ 'addcopy', 'is' => $model->id ]), [ 'title' => Yii::t('items', 'Copy from curren item'), ]);
        };
}

if (User::canPermission('updateRecord'))
{
    // Кнопки
    $template .= ' {delete}';
    // Инвентарный номер
    array_push($columns, [ 'attribute' => 'invent',
        'value' => function ($data)
        {
            return showUrlUpdate($data->invent, $data);
        },
        'format' => 'raw',
    ] );

    // Серийный номер
    array_push($columns, [ 'attribute' => 'serial',
        'value' => function ($data)
        {
            return showUrlUpdate($data->serial, $data);
        },
        'format' => 'raw',
    ] );

    // Модель
    array_push($columns, [ 'attribute' => 'modelName',
        'value' => function ($data)
        {
            if ($data->name == '')
            {
                return showUrlUpdate($data->modelName, $data);
            }
            else
            {
                return showUrlUpdate($data->modelName . ' (' . $data->name . ')', $data);
            }
        },
        'format' => 'raw',
    ] );

    /* // Идентификатор записи
    array_push($columns, [ 'attribute' => 'id',
        'value' => function ($data)
        {
            return showUrlUpdate($data->id, $data);
        },
        'format' => 'raw',
    ] ); // */

    /* // Сетевое имя
    array_push($columns, [ 'attribute' => 'name',
        'value' => function ($data)
        {
            return showUrlUpdate($data->name, $data);
        },
        'format' => 'raw',
    ] ); // */

    // Название подразделения
    array_push($columns, [ 'attribute' => 'regionName',
        'value' => function ($data)
        {
            return showUrlUpdate($data->regionName . ' (' . $data->locationName . ')', $data);
        },
        'format' => 'raw',
    ] ); // */

    // Состояние
    array_push($columns, [ 'attribute' => 'statusName',
        'filter' => Html::activeDropDownList(
            $searchModel,
            'statusName',
            ArrayHelper::merge(
                [ '' => Yii::t('app', 'All statuses'), ],
                ArrayHelper::map(Status::find()->orderBy('name')->all(), 'name', 'name')
            ),
            [ 'class' => 'form-control', ],
        ) ,
        'value' => function ($data)
        {
            return showUrlUpdate($data->statusName, $data);
        },
        'format' => 'raw',
    ] ); // */

    /* // Операционная система
    array_push($columns, [ 'attribute' => 'os',
        'value' => function ($data)
        {
            return showUrlUpdate($data->os, $data);
        },
        'format' => 'raw',
    ] ); // */

    /* // МАС - адрес
    array_push($columns, [ 'attribute' => 'mac',
        'value' => function ($data)
        {
            return showUrlUpdate($data->mac, $data);
        },
        'format' => 'raw',
    ] ); // */

    /* // Примечания
    array_push($columns, [ 'attribute' => 'comment',
        'value' => function ($data)
        {
            return showUrlUpdate($data->comment, $data);
        },
        'format' => 'raw',
    ] ); // */


}
else
{
    array_push($columns, 'invent');
    array_push($columns, 'serial');
    array_push($columns, 'modelName');
    //array_push($columns, 'id');
    //array_push($columns, 'name');
    array_push($columns, [ 'attribute' => 'regionName',
        'value' => function($data)
        {
            return $data->regionName .  ' (' . $data->locationName . ')';
        },
    ]);
    array_push($columns, [ 'attribute' => 'statusName',
        'filter' => Html::activeDropDownList(
            $searchModel,
            'statusName',
            ArrayHelper::merge(
                [ '' => Yii::t('app', 'All statuses') ],
                ArrayHelper::map(Status::find()->orderBy('name')->all(), 'name', 'name')),
            [ 'class' => 'form-control' ],
        ),
        'value' => 'statusName',
    ]);
    //array_push($columns, 'os');
    //array_push($columns, 'mac');
    //array_push($columns, 'product');
    //array_push($columns, 'modelnumber');
    //array_push($columns, 'comment');
}

if (User::canPermission('takingInventory'))
{
    // Кнопки
    $template .= ' {print}';
    $buttons[ 'print' ] = function ($url, $model, $key)
        {
            return Html::a('<span class="glyphicon glyphicon-print"></span>',
                Url::to([ 'print', 'id[]' => $model->id ], 'http'),
                [ 'target' => '_blank',
                    'onclick' => 'markToPrint($(this));',
                    'data-pjax' => 0,
                    'title' => Yii::t('items', 'Print selected labels'),
                ]);
        };
    // Чекбоксы для выбора предметов/оборудования для печати QR меток
    array_push($columns,
            [ 'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function($model, $key, $index, $column)
                    {
                        return [ 'value' => $model->id, ];
                    },
            ]
    );
}

array_push($columns, [
    'class' => 'yii\grid\ActionColumn',
    'template' => $template,
    'buttons' => $buttons,
    'header' => Html::dropDownList(
            'pageSize', $pageSize, [
                20 => 20,
                50 => 50,
                100 => 100,
                0 => 'Все',
            ],
            [ 'onChange' => '$.pjax.reload({container: "#ItemsTable", url: $(location).attr("href") + "&pageSize=" + $(this).val()});',
              'title' => Yii::t('app', 'Lines per page'),
            ]
        ),
]);

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
        <?php // Кнопки на форме 
            if (User::canPermission('createRecord'))
            {
        ?>
        <div class="col-md-2"><?= Html::a(Yii::t('items', 'Create Items'),
            [ 'create' ],
            [ 'class' => 'btn btn-success' ]) ?></div>
            <?php
            }
            if (User::canPermission('updateRecord'))
            {
            ?>
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
            <?php
            }
            if (User::canPermission('takingInventory'))
            {
            ?>
        <div class="col-md-2"><?= Html::a(Yii::t('items', 'Start checking'),
            [ 'start_checking' ],
            [ 'class' => 'btn btn-info',
            ]) ?></div>
        <?php
            }
        ?>
    </div>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'id' => 'ItemsTable',
        'pager' => [
            'class' => 'app\widgets\MyLinkPager',
        ],
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
        'columns' => $columns,
    ]); ?>
    <?php Pjax::end(); ?>

</div>
