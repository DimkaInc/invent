<?php

use yii\helpers\Html;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Regions */

if (! User::canPermission('createRecord'))
{
    return $this->redirect(['site/index']);
}
$this->title = Yii::t('regions', 'Create region');
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
