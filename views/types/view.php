<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Types */

if (! User::canPermission('updateRecord'))
{
    return $this->redirect(['index']);
}
$this->title = $model->name;
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('items', 'Items'), 'url' => [ 'items/index' ]];
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('models', 'Models'), 'url' => [ 'models/index' ]];
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('types', 'Types'), 'url' => [ 'index' ]];
$this->params[ 'breadcrumbs' ][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="types-view">

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
            'id',
            'name',
        ],
    ]) ?>

</div>
