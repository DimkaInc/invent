<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Status;
use app\models\Types;
use app\models\Locations;
use app\models\Regions;
use app\models\Models;

use xj\qrcode\QRcode;
use xj\qrcode\widgets\Email;
use xj\qrcode\widgets\Text;

use yii\data\Sort;

/* @var $this yii\web\View */
/* @var $model app\models\Items */
/* @var $form yii\widgets\ActiveForm */
//$this->registerJsFile("@web/js/regiondatepicker.js", ['depends' => [ \yii\web\JqueryAsset::className() ]] );

    // Создание сортированного списка для выбора типов оборудования
    $models = ArrayHelper::map(Models::find()->orderBy('name')->all(), 'id', 'name');

    if ($model->isNewRecord)
    {
        // Создание сортированного списка для выбора состояний оборудования
        $states = ArrayHelper::map(Status::find()->orderBy('name')->all(), 'id', 'name');
        $defState = [ 'options' => [Status::findOne([ 'name' => 'Склад' ])->id => [ 'Selected' => 'true' ] ]];

        // Создание сортированного списка для выбора расположения оборудования
        $locations = ArrayHelper::map(Locations::find()->joinWith('regions')->orderBy('name')->all(), 'id', 'name');
        foreach ($locations as $key => $val) {
            $locations[ $key ] = $val . ' (' .
                Regions::findOne(['id' => Locations::findOne(['id' => $key])->region_id])->name .
                ')';
        }
        // Значения по умолчанию для случая создания нового
        $location = Locations::findOne([ 'name' => 'Матвейково' ]);
        if (isset($location))
        {
            $defLocate = [ 'options' => [ 'empty' => [ 'Disabled' => 'true' ], $location->id => [ 'Selected' => 'true' ]] ];
        }
        else
        {
            $defLocate = [ 'options' => [ 'empty' => [ 'Disabled' => 'true' ]] ];
        }


        $modelm->date = date('d.m.Y'); // Текущая дата по умолчанию
    } else {
        // Показ QR-кода инвентаризации
        echo '<div style="border: lightgray;border-style: double;display: inline-table;padding: 5px;width: 400px;margin: 0 0 20px;border-radius: 15px;">'.
            '<div style="display:table-cell">' . Text::widget([
            'outputDir' => '@webroot/upload/qrcode',
            'outputDirWeb' => '@web/upload/qrcode',
            'text' => $model->invent . ', ' . $model->serial,
            'size' => 3,
            'margin' => 4,
            'ecLevel' => QRcode::QR_ECLEVEL_L,
        ]) . '</div><div style="display:table-cell;vertical-align:middle;font-size: 18pt;font-weight: bolder;text-align:center;">' . $model->invent . ', ' . $model->models->name .
        '</div></div>';
    }

?>


<div class="items-form">


    <?php $form = ActiveForm::begin([ 'id' => 'ItemForm' ]); ?>

    <?= $form->field($model,
            'model_id',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-5">{input}</div><div class="col-md-2">' .
                       Html::a(Yii::t('models', 'Models'), [ 'models/index' ], [ 'class' => 'btn btn-primary' ] ) .
                       '</div><div class="col-md-8">{error}</div></div>' ])
            ->dropDownList( $models, ['prompt' => Yii::t('models', 'Select model')] )
            ->label(Yii::t('items', 'Model')) ?>

    <?= $form->field($model,
            'name',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>' ])
            ->textInput([ 'maxlength' => true ]) ?>

    <?= $form->field($model,
            'os',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>' ])
            ->textInput([ 'maxlength' => true ]) ?>

    <?= $form->field($model,
            'mac',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>' ])
            ->textInput([ 'maxlength' => true ]) ?>

    <?= $form->field($model,
            'serial',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>' ])
            ->textInput([ 'maxlength' => true ]) ?>

    <?= $form->field($model,
            'invent',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>' ])
            ->textInput([ 'maxlength' => true ]) ?>
    <?php
        // Если предмет/оборудование создаётся, то покажем поля даты, состояния, местоположения
        if ( isset($modelm))
        {
    ?>
        <?= $form->field( $modelm,
                'date',
                [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>' ] )
                ->widget(
                    DatePicker::className(),
                    [ 'language'   => 'ru',
                      'dateFormat' => 'dd.MM.yyyy',
                    ],
                ) ;
        ?>

        <?= $form->field($modelm,
                'state_id',
                [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-2">{input}</div><div class="col-md-8">{error}</div></div>' ])
                ->dropDownList($states, $defState)
                ->label(Yii::t('status', 'Status')) ?>

        <?= $form->field($modelm,
                'location_id',
                [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-5">{input}</div><div class="col-md-2">' .
                    Html::a(
                        Yii::t('locations', 'Locations'),
                        [ 'locations/index' ],
                        [ 'class' => 'btn btn-primary' ]
                    ) .
                    '</div><div class="col-md-8">{error}</div></div>' ])
                ->dropDownList($locations, $defLocate, ['prompt' => Yii::t('locations', 'Select location')])
                ->label(Yii::t('locations', 'Location')) ?>

    <?php
        }
    ?>
    <?= $form->field($model,
            'comment',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>' ])
            ->textarea(['rows' => '10']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'),   [ 'class' => 'btn btn-success' ]) ?>
        <?= Html::a(           Yii::t('app', 'Cancel'), [ 'index' ], [ 'class' => 'btn btn-primary' ]) ?>
    </div>


    <?php ActiveForm::end();

        // Если предмет/оборудование редактируется, то покажем все его движения
        if ( ! isset($modelm) )
        {

    ?>
        <H3><?= Yii::t('moving', 'Movings') ?></H3>

        <p>
            <?= Html::a(Yii::t('moving', 'Create Moving'), [ 'moving/create', 'item_id' => $model->id ], [ 'class' => 'btn btn-success' ]) ?>
        </p>

    <?php
            $GLOBALS['count'] = 0;
            echo GridView::widget([
                'id' => 'MovingTable',
                'dataProvider' => $dataProviderM,
                'filterModel' => $searchModelM,
                'columns' => [

//                    'id',
                    [ 'attribute' => 'date',
                        'value' => function($data)
                        {
                            return Html::a(Html::encode(Yii::$app->formatter->asDate($data->date, 'dd.MM.y')), [ 'moving/update', 'id' => $data->id ]);
                        },
                    'format' => 'raw',
                    ],
//                    'item_id',
                    [ 'attribute' => 'itemModel',
                        'value' => function($data)
                        {
                            return Html::a(Html::encode($data->items->invent . ' (' .$data->itemModel . ')'), [ 'moving/update', 'id' => $data->id ]);
                        },
                        'format' => 'raw',
                    ],

                    [ 'attribute' => 'locationName',
                        'value' => function($data)
                        {
                            return Html::a(Html::encode($data->locationName . ' (' .$data->regionName . ')'), [ 'moving/update', 'id' => $data->id ]);
                        },
                        'format' => 'raw',
                    ],

                    [ 'attribute' => 'statusName',
                        'value' => function($data)
                        {
                            return Html::a(Html::encode($data->statusName), [ 'moving/update', 'id' => $data->id ]);
                        },
                        'format' => 'raw',
                    ],
//                    'comment:ntext',

                    [ 'class' => 'yii\grid\ActionColumn',
                        'template' => '{delete}',
                        'buttons' => [
                            'delete' => function ($url, $model, $index)
                            {
                                if ($GLOBALS['count']++ != 0)
                                {
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', [ 'moving/delete', 'id' => $model->id ], 
                                        [ 'data' => [ 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'method' => 'post', ], ]);
                                } else { return ''; }
                            },
                        ],
                    ],
                ],
            ]);

        }
    ?>


</div>
