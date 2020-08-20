<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Locations */

$this->title = Yii::t('locations', 'Update Locations: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('items', 'Items'), 'url' => ['items/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('locations', 'Locations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="locations-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
