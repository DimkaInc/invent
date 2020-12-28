<?php

use yii\helpers\Html;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Items */

if (! User::canPermission('createRecord'))
{
    return Yii::$app->response->redirect(['site/index']);
}
$this->title = Yii::t('items', 'Create Items');
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('app', 'Items'), 'url' => [ 'index' ]];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="items-create">

     <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelm' => $modelm,
    ]) ?>

</div>
