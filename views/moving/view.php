<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Moving */

$this->title = $model->items->invent . $model->date;
$this->params['breadcrumbs'][] = ['label' => Yii::t('items', 'Items'), 'url' => ['items/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('items', 'Items'), 'url' => ['items/update', 'id' => $model->item_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('moving', 'Movings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="moving-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
            'date',
            'item_id',
            'location_id',
            'state_id',
            'comment:ntext',
        ],
    ]) ?>

</div>
