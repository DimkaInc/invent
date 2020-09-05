<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Locations */

$this->title = Yii::t('locations', 'Create Locations');
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('items', 'Items'),         'url' => [ 'items/index' ]];
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('locations', 'Locations'), 'url' => [ 'index' ]];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="locations-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

</div>
