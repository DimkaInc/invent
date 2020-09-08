<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
//use yii\widgets\Pjax;

//use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
//use app\models\Status;

use app\models\Locations;
use app\models\Regions;
use app\models\LocationsSearch;
use app\models\RegionsSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('items', 'Items');
$this->params[ 'breadcrumbs' ][] = $this->title;

// Создание сортированного списка для выбора расположения оборудования
$locations = ArrayHelper::map(LocationsSearch::noinvent()->orderBy('name')->all(), 'id', 'name');
foreach ($locations as $key => $val) {
    $locations[ $key ] = $val . ' (' .
        Regions::findOne(['id' => Locations::findOne(['id' => $key])->region_id])->name .
        ')';
}

$regions = ArrayHelper::map(RegionsSearch::noinvent()->orderBy('name')->all(), 'id', 'name');
?>

<script language="JavaScript">
    function getData()
    {
        $("form").submit();
    }
</script>

<div class="items-index">

    <!-- h1><?= Html::encode($this->title) ?></h1 -- >
    <pre>
        <?= $dataProvider->query->createCommand()->sql ?>
    </pre -->
    
    <p>
        <?= $message ?>
    </p>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model,
            'qrcheck',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-5">{input}</div><div class="col-md-2">' .
                 Html::submitButton(Yii::t('app', 'Register'),   [ 'class' => 'btn btn-success' ]) .
                '</div><div class="col-md-8">{error}</div></div>' ])
            ->textInput() ?>

    <?= $form->field($model,
            'region',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-5">{input}</div><div class="col-md-2">' .
                '</div><div class="col-md-8">{error}</div></div>' ])
            ->dropDownList($regions, [ 'onchange' => 'getData();', 'class' => 'form-control', 'prompt' => Yii::t('regions', 'Select region') ]) ?>

    <?= $form->field($model,
            'location',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-5">{input}</div><div class="col-md-2">' .
                '</div><div class="col-md-8">{error}</div></div>' ])
            ->dropDownList($locations, [ 'onchange' => 'getData();', 'class' => 'form-control', 'prompt' => Yii::t('locations', 'Select location') ]) ?>


    <?php $form = ActiveForm::end(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider, // Источник данных
        'columns' => [

            // Инвентарный номер
            'invent',

            // Серийный номер
            'serial',
            // Модель
            'model',

            // Название подразделения
            
            [ 'attribute' => 'regionName',
                'value' => function($data)
                    {
                        return $data->regionName .  ' (' . $data->locationName . ')';
                    },
            ],

            // Состояние
            'statusName',

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

        ],
    ]); ?>

</div>
