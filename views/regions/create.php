<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Regions */

$this->title = Yii::t('regions', 'Create Regions');
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('items',     'Items'),     'url' => [ 'items/index' ]];
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('locations', 'Locations'), 'url' => [ 'locations/index' ]];
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('regions',   'Regions'),   'url' => [ 'index' ]];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="regions-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
