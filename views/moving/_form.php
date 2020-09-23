<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use yii\jui\DatePicker;


use app\models\Status;
use app\models\Locations;
use app\models\Regions;

/* @var $this yii\web\View */
/* @var $model app\models\Moving */
/* @var $form yii\widgets\ActiveForm */


    // Создание сортированного списка для выбора состояний оборудования
    $states = ArrayHelper::map(Status::find()->orderBy('name')->all(), 'id', 'name');

    $defState = [];

    // Создание сортированного списка для выбора расположения оборудования
    $locations[ 'empty' ] = Yii::t('locations', 'Select location');
    $locArray = ArrayHelper::map(Locations::find()->joinWith('regions')->orderBy('name')->all(), 'id', 'name');
    foreach ($locArray as $key => $val)
    {
        $locArray[$key] = $val . ' (' .
            Regions::findOne([ 'id' => Locations::findOne(['id' => $key])->region_id ])->name .
            ')';
    }
    $locations = ArrayHelper::merge($locations, $locArray);
    $defLocate = [ 'options' => [ 'empty' => [ 'Disabled' => 'true' ], ]];


    // Создание сортированного списка для выбора состояний оборудования
    $states = ArrayHelper::map(Status::find()->orderBy('name')->all(), 'id', 'name');

    // Значения по умолчанию для случая создания нового

    $minDate = date('d.m.Y', strtotime('1990-01-01'));
    $maxDate = date('d.m.Y');

    if ($model->isNewRecord)
    {
        $defState    = [ 'options' => [ Status::findOne([ 'name' => 'Работает' ])->id => [ 'Selected' => 'true' ] ]];
        $defLocate   = [ 'options' => [ 'empty' => [ 'Disabled' => 'true' ], Locations::findOne([ 'name' => 'Матвейково' ])->id => [ 'Selected' => 'true' ]] ];
        $model->date = date('d.m.Y');
    }


?>

<div class="moving-form">

    <?php $form = ActiveForm::begin([
        'id' => 'MovingForm',
    ]); ?>

    <?= $form->field($model, 'date', [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>' ])
        ->widget(
            DatePicker::className(),
            [ 'language'   => 'ru',
              'dateFormat' => 'dd.MM.yyyy',
              'clientOptions' => [
              'todayHighlight' => true,
              'minDate' => $minDate,
              'maxDate' => $maxDate,
              ],
            ],
        ) ?>

    <?= $form->field($model, 'item_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model,
            'state_id',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-2">{input}</div><div class="col-md-8">{error}</div></div>' ])
            ->dropDownList($states, $defState)
            ->label(Yii::t('status', 'Status')) ?>

    <?= $form->field(
            $model,
            'location_id',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-5">{input}</div><div class="col-md-2">' .
                Html::a(
                    Yii::t('locations', 'Locations'),
                    ['locations/index'],
                    ['class' => 'btn btn-primary']) .
                '</div><div class="col-md-8">{error}</div></div>'])
            ->dropDownList($locations, $defLocate)
            ->label(Yii::t('locations', 'Location'))
    ?>

    <?= $form->field($model, 'comment')->textarea([ 'rows' => 6 ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'),   [ 'class' => 'btn btn-success' ]) ?>
        <?= Html::a(           Yii::t('app', 'Cancel'), [ 'items/update', 'id' => $model->item_id ], [ 'class' => 'btn btn-primary' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
