<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Regions;

/* @var $this yii\web\View */
/* @var $model app\models\Locations */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="locations-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model,
        'region_id',
        ['template' => '<div class="row"><div class="col-md-3">{label}</div><div class="col-md-4">{input}</div><div class="col-md-2">' .
                       Html::a("Регионы (подразделения)", ['regions/index'], ['class' => 'btn btn-primary'] ) .
                       '</div><div class="col-md-8">{error}</div></div>']
        )->dropDownList(ArrayHelper::merge(['empty' => "Выберите регион (подразделение)"],
           ArrayHelper::map(Regions::find()->all(),
           'id',
           'name'))) ?>


    <?= $form->field($model,
            'name',
            ['template' => '<div class="row"><div class="col-md-3">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>']
        )->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-primary']) ?>
     </div>

    <?php ActiveForm::end(); ?>

</div>
