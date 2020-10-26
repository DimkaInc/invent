<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

use app\models\Regions;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LocationsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

if (! User::canPermission('createRecord'))
{
    return $this->redirect(['site/index']);
}

$this->title = Yii::t('locations', 'Locations');
$this->params[ 'breadcrumbs' ][] = [ 'label' => Yii::t('items', 'Items'), 'url' => [ 'items/index' ]];
$this->params[ 'breadcrumbs' ][] = $this->title;

$columns = [[ 'class' => 'yii\grid\SerialColumn' ]];
$template = '';

// Кнопки и колонки
if (User::canPermission('updateRecord'))
{
    $template = '{delete}';
    // Название места размещения
    array_push($columns, [ 'attribute' => 'name',
                'value' => function ($data)
                {
                    return showUrlUpdate($data->name, $data);
                },
                'format' => 'raw',
    ]);

    // Название региона
    array_push($columns, [
        'attribute' => 'regionName',
        'filter' => Html::activeDropDownList(
            $searchModel,
            'regionName',
            ArrayHelper::merge([ '' => Yii::t('locations', 'All regions') ], ArrayHelper::map(Regions::find()->orderBy('name')->all(), 'name', 'name')),
            [ 'class' => 'form-control' ],
        ),
        'value' => function ($data)
        {
            return showUrlUpdate($data->regions->name, $data);
        },
        'format' => 'raw',
    ]);
} else
{
    // Название места размещения
    array_push($columns, 'name');

    // Название региона
    array_push($columns, [
        'attribute' => 'regionName',
        'filter' => Html::activeDropDownList(
            $searchModel,
            'regionName',
            ArrayHelper::merge([ '' => Yii::t('locations', 'All regions') ], ArrayHelper::map(Regions::find()->orderBy('name')->all(), 'name', 'name')),
            [ 'class' => 'form-control' ],
        ),
        'value' => function ($data)
        {
            return $data->regions->name;
        },
    ]);
}

array_push($columns, [ 'class' => 'yii\grid\ActionColumn', 'template' => $template ]);
?>
<div class="locations-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('locations', 'Create location'), [ 'create' ], [ 'class' => 'btn btn-success' ]) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'id'           => 'LocationsTable',
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $columns,
    ]); ?>

    <?php Pjax::end(); ?>

</div>
