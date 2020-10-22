<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\User;

if (! User::canPermission('createRecord'))
{
    return $this->redirect(['site/index']);
}
$this->title = Yii::t('status', 'Statuses');
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
<div class="status-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('status', 'Create status'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'id' => 'StatusTable',
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $columns,
    ]); ?>

    <?php Pjax::end(); ?>

</div>
