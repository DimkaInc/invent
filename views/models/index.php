<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ModelsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('models', 'Models');
$this->params['breadcrumbs'][] = $this->title;

$columns = [[ 'class' => 'yii\grid\SerialColumn' ]];
 // Кнопки действий {view} {update} {delete} {print}
$template = '';
$buttons = [];
if (User::canPermission('updateRecord'))
{
    // Кнопки
    $template .= ' {delete}';
    // Наименование
    array_push($columns, [ 'attribute' => 'name',
        'value' => function ($data)
        {
            return showUrlUpdate($data->name, $data);
        },
        'format' => 'raw',
    ] );
    // Тип модели
    array_push($columns, [ 'attribute' => 'typeName',
        'value' => function ($data)
        {
            return showUrlUpdate($data->typeName, $data);
        },
        'format' => 'raw',
    ] );
    // Номер модели
    array_push($columns, [ 'attribute' => 'modelnum',
        'value' => function ($data)
        {
            return showUrlUpdate($data->modelnum, $data);
        },
        'format' => 'raw',
    ] );
    // Код продукта
    array_push($columns, [ 'attribute' => 'product',
        'value' => function ($data)
        {
            return showUrlUpdate($data->product, $data);
        },
        'format' => 'raw',
    ] );
    array_push($columns, [ 'class' => 'yii\grid\ActionColumn', 'template' => $template ]);
}
else
{
    array_push($columns, 'name');
    array_push($columns, 'typeName');
    array_push($columns, 'modelnum');
    array_push($columns, 'product');
}
?>
<div class="models-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('models', 'Create Model'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>

    <?php Pjax::end(); ?>

</div>
