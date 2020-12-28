<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Items */

if (! User::canPermission('updateRecord'))
{
    return Yii::$app->response->redirect(['site/index']);
}


$this->title = $model->invent;
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('app', 'Items'), 'url' => [ 'index' ]];
$this->params[ 'breadcrumbs' ][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="items-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), [ 'update', 'id' => $model->id ], [ 'class' => 'btn btn-primary' ]) ?>
        <?= Html::a(Yii::t('app', 'Delete'), [ 'delete', 'id' => $model->id ], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            'name',
            'modelName',
            'os',
            'mac',
            'serial',
            'invent',
            'date',
            'comment'
        ],
    ]) ?>

</div>
