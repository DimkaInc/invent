<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TypesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

if (! User::canPermission('createRecord'))
{
    return $this->redirect(['site/index']);
}
$this->title = Yii::t('types', 'Types');
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('items', 'Items'), 'url' => [ 'items/index' ]];
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('models', 'Models'), 'url' => [ 'models/index' ]];
$this->params[ 'breadcrumbs' ][] = $this->title;

$columns = [[ 'class' => 'yii\grid\SerialColumn' ]];
$template = '';
if (User::canPermission('updateRecord'))
{
    $template .= '{delete}';
    array_push($columns, [
        'attribute' => 'name',
        'value'   => function ($data)
        {
            return showUrlUpdate($data->name, $data);
        },
        'format'  => 'raw',
    ]);
} else
{
    array_push($columns, 'name');
}
array_push($columns, [ 'class' => 'yii\grid\ActionColumn', 'template' => $template ]);

?>
<div class="types-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('types', 'Create type'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'id' => 'TypesTable',
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $columns,
    ]); ?>

    <?php Pjax::end(); ?>

</div>
