<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
//use yii\widgets\Pjax;

//use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
//use app\models\Status;

use app\models\Locations;
use app\models\Regions;
use app\models\LocationsSearch;
use app\models\RegionsSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('items', 'Items');
$this->params[ 'breadcrumbs' ][] = $this->title;

// Создание сортированного списка для выбора расположения оборудования
$locations = ArrayHelper::map(LocationsSearch::noinvent()->orderBy('name')->all(), 'id', 'name');
foreach ($locations as $key => $val) {
    $locations[ $key ] = $val . ' (' .
        Regions::findOne(['id' => Locations::findOne(['id' => $key])->region_id])->name .
        ')';
}

$regions = ArrayHelper::map(RegionsSearch::noinvent()->orderBy('name')->all(), 'id', 'name');
?>

<script language="JavaScript">
    function getData()
    {
        $("form").submit();
    }
    
/*    window.onload = function () {
    var canvas = document.getElementById('canvas');
    var video = document.getElementById('video');
    var button = document.getElementById('button');
    var allow = document.getElementById('allow');
    var context = canvas.getContext('2d');
    var videoStreamUrl = false;

    // функция которая будет выполнена при нажатии на кнопку захвата кадра
    var captureMe = function () {
      if (!videoStreamUrl) alert('То-ли вы не нажали "разрешить" в верху окна, то-ли что-то не так с вашим видео стримом')
      // переворачиваем canvas зеркально по горизонтали (см. описание внизу статьи)
      context.translate(canvas.width, 0);
      context.scale(-1, 1);
      // отрисовываем на канвасе текущий кадр видео
      context.drawImage(video, 0, 0, video.width, video.height);
      // получаем data: url изображения c canvas
      var base64dataUrl = canvas.toDataURL('image/png');
      context.setTransform(1, 0, 0, 1, 0, 0); // убираем все кастомные трансформации canvas
      // на этом этапе можно спокойно отправить  base64dataUrl на сервер и сохранить его там как файл (ну или типа того) 
      // но мы добавим эти тестовые снимки в наш пример:
      var img = new Image();
      img.src = base64dataUrl;
      window.document.body.appendChild(img);
    }

    button.addEventListener('click', captureMe);

/*
    async function getMedia(constraints) {
        let stream = null;

        try {
            stream = await navigator.mediaDevices.getUserMedia(constraints);
            allow.style.display = "none";
            videoStreamUrl = window.URL.createObjectURL(stream);
            video.src = videoStreamUrl;
            //* используем поток 
        } catch(err) {
            //* обработка ошибки
            console.log('что-то не так с видеостримом или пользователь запретил его использовать :P');
        }
    }
    getMedia({video: true}); // * /
/*
    // navigator.getUserMedia  и   window.URL.createObjectURL (смутные времена браузерных противоречий 2012)
    navigator.getUserMedia = (
        navigator.getUserMedia ||
        navigator.webkitGetUserMedia ||
        navigator.mozGetUserMedia ||
        navigator.msGetUserMedia); // * /
//    window.URL.createObjectURL = window.URL.createObjectURL || window.URL.webkitCreateObjectURL || window.URL.mozCreateObjectURL || window.URL.msCreateObjectURL;
/*
    // запрашиваем разрешение на доступ к поточному видео камеры
    navigator.getUserMedia({video: true}, function (stream) {
      // разрешение от пользователя получено
      // скрываем подсказку
      allow.style.display = "none";
      // получаем url поточного видео
      videoStreamUrl = window.URL.createObjectURL(stream);
      // устанавливаем как источник для video 
      video.src = videoStreamUrl;
    }, function () {
      console.log('что-то не так с видеостримом или пользователь запретил его использовать :P');
    });
    // * /
    navigator.getUserMedia = navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia;

    if (navigator.getUserMedia) {
    navigator.getUserMedia({ audio: false, video: { width: 640, height: 480 } },
      function(stream) {
         var video = document.querySelector('video');
         video.srcObject = stream;
         video.onloadedmetadata = function(e) {
           video.play();
         };
      },
      function(err) {
         console.log("The following error occurred: " + err.name);
      }
   );
} else {
   console.log("getUserMedia not supported");
}    
  }; // */
</script>

<style>
    video{
/*          transform: scaleX(-1);
       -o-transform: scaleX(-1);
      -ms-transform: scaleX(-1);
     -moz-transform: scaleX(-1);
  -webkit-transform: scaleX(-1); */
}
</style>

<div class="items-index">

    <!-- h1><?= Html::encode($this->title) ?></h1 -- >
    <pre>
        <?= $dataProvider->query->createCommand()->sql ?>
    </pre -- >
    <div id="allow"> Разрешите использовать камеру<br />(сверху текущей страницы)</div>
    <div class="item">
        <span> video </span>
        <video id="video" autoplay="autoplay"></video>
    </div>
    
    <div class="item">
        <span> canvas </span>
        <canvas id="canvas" width="320" height="240"></canvas>
    </div>
    <input id="button" type="button" value="Поехали!" / -->
    <p>
        <?= $message ?>
    </p>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model,
            'qrcheck',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-5">{input}</div><div class="col-md-2">' .
                 Html::submitButton(Yii::t('app', 'Register'),   [ 'class' => 'btn btn-success' ]) .
                '</div><div class="col-md-8">{error}</div></div>' ])
            ->textInput() ?>

    <?= $form->field($model,
            'region',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-5">{input}</div><div class="col-md-2">' .
                '</div><div class="col-md-8">{error}</div></div>' ])
            ->dropDownList($regions, [ 'onchange' => 'getData();', 'class' => 'form-control', 'prompt' => Yii::t('regions', 'Select region') ]) ?>

    <?= $form->field($model,
            'location',
            [ 'template' => '<div class="row"><div class="col-md-2">{label}</div><div class="col-md-5">{input}</div><div class="col-md-2">' .
                '</div><div class="col-md-8">{error}</div></div>' ])
            ->dropDownList($locations, [ 'onchange' => 'getData();', 'class' => 'form-control', 'prompt' => Yii::t('locations', 'Select location') ]) ?>


    <?php $form = ActiveForm::end(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider, // Источник данных
        'columns' => [

            // Инвентарный номер
            'invent',

            // Серийный номер
            'serial',
            // Модель
            'model',

            // Название подразделения
            
            [ 'attribute' => 'regionName',
                'value' => function($data)
                    {
                        return $data->regionName .  ' (' . $data->locationName . ')';
                    },
            ],

            // Состояние
            'statusName',

            /* // Операционная система
            [ 'attribute' => 'os',
                'value' => function ($data)
                    {
                        return showUrlUpdate($data->os, $data->id);
                    },
                'format' => 'raw',
            ], // */

            /* // МАС - адрес
            [ 'attribute' => 'mac',
                'value' => function ($data)
                    {
                        return showUrlUpdate($data->mac, $data);
                    },
                'format' => 'raw',
            ], // */

            /* // Код товара
            [ 'attribute' => 'product',
                'value' => function ($data)
                    {
                        return showUrlUpdate($data->product, $data);
                    },
                'format' => 'raw',
            ], // */

            /* // Номер модели
            [ 'attribute' => 'modelnumber',
                'value' => function ($data)
                    {
                        return showUrlUpdate($data->modelnumber, $data);
                    },
                'format' => 'raw',
            ], // */

            /* // Примечания
            [ 'attribute' => 'comment',
                'value' => function ($data)
                    {
                        return showUrlUpdate($data->comment, $data);
                    },
                'format' => 'raw',
            ], // */

        ],
    ]); ?>

</div>
