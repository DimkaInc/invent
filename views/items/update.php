<?php

use yii\helpers\Html;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Items */

if (! User::canPermission('updateRecord'))
{
    return $this->redirect(['site/index']);
}

$this->title = Yii::t('items', 'Update Items: {name}', [
    'name' => $model->invent,
]);
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('app', 'Items'), 'url' => [ 'index' ]];
$this->params[ 'breadcrumbs' ][] = Yii::t('app', 'Update');
?>
<div class="items-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'searchModelM' => $searchModelM,
        'dataProviderM' => $dataProviderM,
        'model' => $model,
    ]) ?>

</div>
