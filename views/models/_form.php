<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;

use app\models\Types;

/* @var $this yii\web\View */
/* @var $model app\models\Models */
/* @var $form yii\widgets\ActiveForm */

$types = ArrayHelper::map(Types::find()->orderBy('name')->all(), 'id', 'name');

?>

<div class="models-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model,
            'name',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>' ])
            ->textInput([ 'maxlength' => true ]) ?>

    <?= $form->field($model,
            'type_id',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-5">{input}</div><div class="col-md-2">' .
                       Html::a(Yii::t('types', 'Types'), [ 'types/index' ], [ 'class' => 'btn btn-primary' ] ) .
                       '</div><div class="col-md-8">{error}</div></div>' ])
            ->dropDownList( $types, ['prompt' => Yii::t('types', 'Select type')] ) ?>


    <?= $form->field($model,
            'product',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>' ])
            ->textInput([ 'maxlength' => true ]) ?>

    <?= $form->field($model,
            'modelnumber',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>' ])
            ->textInput([ 'maxlength' => true ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'),   [ 'class' => 'btn btn-success' ]) ?>
        <?= Html::a(           Yii::t('app', 'Cancel'), [ 'index' ], [ 'class' => 'btn btn-primary' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
