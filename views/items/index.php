<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use app\models\Status;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('items', 'Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="items-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('items', 'Create Items'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // Нумератор строк
            ['class' => 'yii\grid\SerialColumn'],
            
            // Дата внесения(поступления) записи
            ['attribute' => 'date', 
                'value' => function ($data) {
                    return Html::a(Yii::$app->formatter->asDate($data->date, 'd.MM.Y'), Url::to(['update', 'id' => $data->id]));
                },
                'format' => 'raw',
            ],
            
            // Инвентарный номер
            ['attribute' => 'invent',
                'value' => function ($data) {
                    return Html::a(Html::encode($data->invent), Url::to(['update', 'id' => $data->id]));
                },
                'format' => 'raw',
            ],
            
            // Серийный номер
            ['attribute' => 'serial',
                'value' => function ($data) {
                    return Html::a(Html::encode($data->serial), Url::to(['update', 'id' => $data->id]));
                },
                'format' => 'raw',
            ],
            
            // Модель
            ['attribute' => 'model',
                'value' => function ($data) {
                    return Html::a(Html::encode($data->model), Url::to(['update', 'id' => $data->id]));
                },
                'format' => "raw",
            ],
            
            // Идентификатор записи
            //['attribute' => 'id',
            //    'value' => function ($data) {
            //        return Html::a(Html::encode($data->id), Url::to(['update', 'id' => $data->id]));
            //    },
            //    'format' => "raw",
            //],
            
            // Сетевое имя
            ['attribute' => 'name',
                'value' => function ($data) {
                    return Html::a(Html::encode($data->name), Url::to(['update', 'id' => $data->id]));
                },
                'format' => "raw",
            ],
            
            // Состояние
            ['attribute' => 'statusName',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'statusName',
                    ArrayHelper::merge(['' => "Все состояния"], ArrayHelper::map(Status::find()->orderBy('name')->all(), 'name', 'name')),
                    ['class' => 'form-control' ],
                ),
                'value' => function ($data) {
                    return Html::a(Html::encode($data->status->name), Url::to(['update', 'id' => $data->id]));
                },
                'format' => "raw",
            ],
            
            // Операционная система
            //['attribute' => 'os',
            //    'value' => function ($data) {
            //        return Html::a(Html::encode($data->os), Url::to(['update', 'id' => $data->id]));
            //    },
            //    'format' => "raw",
            //],
            
            // МАС - адрес
            //['attribute' => 'mac',
            //    'value' => function ($data) {
            //        return Html::a(Html::encode($data->mac), Url::to(['update', 'id' => $data->id]));
            //    },
            //    'format' => "raw",
            //],
            
            // Код товара
            //['attribute' => 'product',
            //    'value' => function ($data) {
            //        return Html::a(Html::encode($data->product), Url::to(['update', 'id' => $data->id]));
            //    },
            //    'format' => "raw",
            //],
            
            // Номер модели
            //['attribute' => 'modelnumber',
            //    'value' => function ($data) {
            //        return Html::a(Html::encode($data->modelnumber), Url::to(['update', 'id' => $data->id]));
            //    },
            //    'format' => "raw",
            //],
            
            // Примечания
            //['attribute' => 'comment',
            //    'value' => function ($data) {
            //        return Html::a(Html::encode($data->comment), Url::to(['update', 'id' => $data->id]));
            //    },
            //    'format' => "raw",
            //],

            // Кнопки действий {view} {update} {delete}
            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}' ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
