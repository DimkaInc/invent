<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Models */

$this->title = Yii::t('models', 'Update Model: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = [ 'label' => Yii::t('items', 'Items'), 'url' => [ 'items/index' ]];
$this->params['breadcrumbs'][] = [ 'label' => Yii::t('models', 'Models'), 'url' => [ 'index' ]];
//$this->params['breadcrumbs'][] = [ 'label' => $model->name, 'url' => [ 'view', 'id' => $model->id ]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="models-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
