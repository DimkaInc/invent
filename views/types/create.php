<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Types */

$this->title = Yii::t('types', 'Create Types');
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('items', 'Items'), 'url' => [ 'items/index' ]];
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('types', 'Types'), 'url' => [ 'index' ]];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="types-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
