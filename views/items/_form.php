<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Status;
use app\models\Types;
use app\models\Locations;

use yii\data\Sort;

/* @var $this yii\web\View */
/* @var $model app\models\Items */
/* @var $form yii\widgets\ActiveForm */
//$this->registerJsFile("@web/js/regiondatepicker.js", ['depends' => [ \yii\web\JqueryAsset::className() ]] );

    // Создание сортированного списка для выбора типов оборудования
    $types['empty'] = Yii::t('types', 'Select type');
    $types = ArrayHelper::merge($types, ArrayHelper::map(Types::find()->orderBy('name')->all(), 'id', 'name'));
    
    // Создание сортированного списка для выбора состояний оборудования
    $states = ArrayHelper::map(Status::find()->orderBy('name')->all(), 'id', 'name');
    
    // Создание сортированного списка для выбора расположения оборудования
    $locations['empty'] = Yii::t('locations', 'Select location');
    $locations = ArrayHelper::merge($locations, ArrayHelper::map(Locations::find()->orderBy('name')->all(), 'id', 'name'));
?>

<div class="items-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model,
            'type_id',
            ['template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-5">{input}</div><div class="col-md-2">' .
                       Html::a(Yii::t('types', 'Types'), ['types/index'], ['class' => 'btn btn-primary'] ) .
                       '</div><div class="col-md-8">{error}</div></div>']
        )->dropDownList( $types ) ?>

    <?= $form->field($model, 'name', ['template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>'])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'model', ['template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>'])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'os', ['template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>'])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mac', ['template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>'])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'serial', ['template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>'])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product', ['template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>'])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'modelnumber', ['template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>'])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invent', ['template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>'])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date', ['template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>'])->widget(
            DatePicker::className(),
            [ 'language'   => 'ru',
              'dateFormat' => 'dd.MM.yyyy',
            ]
        ) ?>

    <?= $form->field($model,
            'state_id',
            ['template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-2">{input}</div><div class="col-md-8">{error}</div></div>']
        )->dropDownList( $states ) ?>

    <?= $form->field(
            $model,
            'location_id',
            ['template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-5">{input}</div><div class="col-md-2">' .
                Html::a(
                    Yii::t('locations', 'Locations'),
                    ['locations/index'],
                    ['class' => 'btn btn-primary'] 
                ) .
                '</div><div class="col-md-8">{error}</div></div>']
        )->dropDownList($locations)
    ?>

    <?= $form->field($model,
            'comment',
            ['template' => 
                '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>'
            ]
        )->textarea(['rows' => '10']) ?>

    <div class="form-group">
        <?= Html::submitButton( Yii::t('app', 'Save'),   ['class' => 'btn btn-success'] ) ?>
        <?= Html::a(            Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-primary'] ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
