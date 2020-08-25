<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Items */

$this->title = Yii::t('items', 'Update Items: {name}', [
    'name' => $model->invent,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="items-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
