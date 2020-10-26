<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItemsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="items-search">

    <?php $form = ActiveForm::begin([
        'action' => [ 'index' ],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?php //= $form->field($model, 'id') 
    ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'model') ?>

    <?php //= $form->field($model, 'os') 
    ?>

    <?php //= $form->field($model, 'mac') 
    ?>

    <?= $form->field($model, 'serial') ?>

    <?php // echo $form->field($model, 'product'); 
    ?>

    <?php // echo $form->field($model, 'modelnumber'); 
    ?>

    <?= $form->field($model, 'invent') ?>

    <?= $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'comment'); 
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), [ 'class' => 'btn btn-primary' ]) ?>
        <?= Html::resetButton( Yii::t('app', 'Reset'),  [ 'class' => 'btn btn-outline-secondary' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
