<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Login');
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode(Yii::t('app', $this->title)) ?></h1>

    <p><?= Yii::t('app', 'Please fill out the following fields to login:') ?></p>

    <?php $form = ActiveForm::begin([
        'id'          => 'login-form',
        'layout'      => 'horizontal',
        'fieldConfig' => [
            'template'     => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => [ 'class' => 'col-lg-2 control-label' ],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput([ 'autofocus' => true ]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton(Yii::t('app', 'Login'), [ 'class' => 'btn btn-primary', 'name' => 'login-button' ]) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <div class="col-lg-offset-1" style="color:#999;">
        <?= Yii::t('app', 'You may login with') ?> <strong>admin/admin</strong> <?= Yii::t('app', 'or') ?> <strong>demo/demo</strong>.<br>
        <?= Yii::t('app', 'To modify the username/password, please check out the code') ?> <code>app\models\User::$users</code>.
    </div>
</div>
