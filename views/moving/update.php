<?php

use yii\helpers\Html;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Moving */

if (! User::canPermission('updateRecord'))
{
    return $this->redirect(['index']);
}

$this->title = Yii::t('moving', 'Update Moving: {name}', [
    'name' => $model->items->invent . ' (' . $model->items->models->name . ') =>(' . Yii::$app->formatter->asDate($model->date, 'dd.MM.y') . ')',
]);
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('items', 'Items'), 'url' => [ 'items/index' ]];
$this->params[ 'breadcrumbs' ][] = [ 'label' => Html::encode($model->items->invent), 'url' => [ 'items/update', 'id' => $model->item_id ]];
$this->params[ 'breadcrumbs' ][] = Yii::t('app', 'Update');
?>
<div class="moving-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
