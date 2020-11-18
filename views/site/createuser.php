<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('users', 'Create user');
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="form">
        <?php
            $form = ActiveForm::begin();
        ?>
        <?= $form->field($model, 'username',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>' ])
            ->textInput() ?>
        <?= $form->field($model, 'password',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-7">{input}</div><div class="col-md-8">{error}</div></div>' ])
            ->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'),   [ 'class' => 'btn btn-success' ]) ?>
            <?= Html::a(           Yii::t('app', 'Cancel'), [ 'index' ], [ 'class' => 'btn btn-primary' ]) ?>
        </div>
        <?php ActiveForm::end();; ?>
    </div>

</div>
