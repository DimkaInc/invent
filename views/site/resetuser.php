<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('users', 'Reset users passwords');
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'id' => 'UsersTable',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            [ 'class' => 'yii\grid\SerialColumn' ],
            'username',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => ' {edit}',
                'buttons' => [
                    'edit' => function ($url, $model, $key)
                    {
                        return Html::a('<span class="glyphicon glyphicon-fire"></span>',
                            [ 'reset', 'id' => $model->id ],
                            [ 'class' => '',
                              'data'  => [
                                'confirm' => Yii::t('users', 'Are you sure? A reset password cannot be recovered.'),
                                'method'  => 'post',
                              ],
                            ]);
                    },
                ],
            ],
        ],
    ]) ?>

</div>
