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

use xj\qrcode\QRcode;
use xj\qrcode\widgets\Email;
use xj\qrcode\widgets\Text;

use yii\data\Sort;

/* @var $this yii\web\View */
/* @var $model app\models\Items */
/* @var $form yii\widgets\ActiveForm */

?>


<div class="items-form">


    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model,
            'filecsv',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-5">{input}</div><div class="col-md-2">' .
                       '</div><div class="col-md-8">{error}</div></div>' ])
            ->fileInput([ 'accept' => '.csv' ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Import'),   [ 'class' => 'btn btn-success' ]) ?>
        <?= Html::a(           Yii::t('app', 'Cancel'), [ 'index' ], [ 'class' => 'btn btn-primary' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
