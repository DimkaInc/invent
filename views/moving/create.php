<?php

use yii\helpers\Html;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Moving */

if (! User::canPermission('createRecord'))
{
    return $this->redirect(['site/index']);
}
$this->title = Yii::t('moving', 'Create Moving') . ' ' .$model->items->invent . ' (' . $model->items->models->name . ')';
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('items', 'Items'), 'url' => [ 'items/index' ]];
$this->params[ 'breadcrumbs' ][] = [ 'label' => Html::encode($model->items->invent), 'url' => [ 'items/update', 'id' => $model->item_id ]];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="moving-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
