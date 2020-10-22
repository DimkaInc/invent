<?php

use yii\helpers\Html;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Items */

if (! User::canPermission('updateRecord'))
{
    return $this->redirect(['site/index']);
}

$this->title = Yii::t('items', 'Import Items');
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('app', 'Items'), 'url' => [ 'index' ]];
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="items-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $message ?>
    </p>
    <?= $this->render('_formimport', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
