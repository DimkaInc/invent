<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

if (! User::canPermission('createRecord'))
{
    return $this->redirect(['site/index']);
}
$this->title = Yii::t('regions', 'Regions');
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('items',     'Items'),     'url' => [ 'items/index' ]];
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('locations', 'Locations'), 'url' => [ 'locations/index' ]];
$this->params[ 'breadcrumbs' ][] = $this->title;

$columns = [[ 'class' => 'yii\grid\SerialColumn' ]];
$template = '';
if (User::canPermission('updateRecord'))
{
    $template .= '{delete}';
    array_push($columns, [
        'attribute' => 'name',
        'value'  => function ($data)
        {
            return showUrlUpdate($data->name, $data);
        },
        'format' => 'raw',
    ]);
} else
{
    array_push($columns, 'name');
}
array_push($columns, [ 'class' => 'yii\grid\ActionColumn', 'template' => $template ]);

?>
<div class="regions-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('regions', 'Create region'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'id' => 'RegionsTable',
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $columns,
    ]); ?>

    <?php Pjax::end(); ?>

</div>
