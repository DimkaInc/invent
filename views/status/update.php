<?php

use yii\helpers\Html;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Status */

if (! User::canPermission('updateRecord'))
{
    return $this->redirect(['index']);
}
$this->title = Yii::t('status', 'Update Status: {name}', [
    'name' => $model->name,
]);
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('status', 'Statuses'), 'url' => [ 'index' ]];
$this->params[ 'breadcrumbs' ][] = Yii::t('app', 'Update');
?>
<div class="status-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
