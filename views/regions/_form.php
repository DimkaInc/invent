<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Regions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="regions-form">

    <?php $form = ActiveForm::begin([ 'id' => 'RegionsForm' ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton( Yii::t('app', 'Save'),   [ 'class' => 'btn btn-success' ]) ?>
        <?= Html::a(            Yii::t('app', 'Cancel'), [ 'index' ], [ 'class' => 'btn btn-primary' ]) ?>
     </div>

    <?php ActiveForm::end(); ?>

</div>
