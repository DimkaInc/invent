<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use app\models\Status;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Reports');
$this->params[ 'breadcrumbs' ][] = $this->title;

$lastLocation = 0;

$models = $dataProvider->getModels();

function cell($text, $color, $wrap='')
{
    return '<td ' . $wrap . ' style="padding: 2px 2px 2px 2px;"><font color="' . $color . '">' . $text . '</font></td>';
}

// Цветовая палитра состояний
$colors = [ 'Списано' => '#FF0000',
            'К списанию' => '#FF0000',
            'Работает' => '#009F00',
            'Сломано' => '#0000FF',
    ];

foreach ($models as $model)
{
    // Раскраска строк по состоянию
    if (isset($colors[ $model->status->name ]))
    {
        $color = $colors[ $model->status->name ];
    }
    else
    {
        $color='#000000';
    }

    // Смена места хранения
    if ($model->locations->id != $lastLocation)
    {
        // Начало таблицы
        if ($lastLocation == 0)
        {
            ?>
            <table border="1" style="padding: 2px 2px 2px 2px;" width="100%"><tbody>
            <?php
        }

        // Отображение заголовка
        $lastLocation = $model->locations->id;
        ?>
        <tr>
            <td colspan="8" style="padding: 2px 2px 2px 2px;"><H2><?= $model->locations->regions->name ?></H2>
            <H3><?= $model->locations->name ?></H3></td>
        </tr>
        <tr bgcolor="#EFEFEF">
            <th style= "text-align:center"><?= Yii::t('items', 'Inventory number') ?></th>
            <th style= "text-align:center"><?= Yii::t('items', 'Serial number') ?></th>
            <th style= "text-align:center"><?= Yii::t('items', 'Items') ?></th>
            <th style= "text-align:center"><?= Yii::t('types', 'Type') ?></th>
            <th style= "text-align:center"><?= Yii::t('status', 'Status') ?></th>
            <th style= "text-align:center"><?= Yii::t('items', 'Item network name') ?></th>
            <th style= "text-align:center"><?= Yii::t('items', 'Operating system') ?></th>
            <th style= "text-align:center"><?= Yii::t('items', 'MAC address') ?></th>
        </tr>
        <?php
    }

    // Отображение строки
    ?>
    <tr>
        <?= cell($model->items->invent, $color) ?>
        <?= cell($model->items->serial, $color) ?>
        <?= cell($model->items->models->name, $color) ?>
        <?= cell($model->items->models->types->name, $color) ?>
        <?= cell($model->status->name, $color, 'nowrap') ?>
        <?= cell($model->items->name, $color, 'nowrap') ?>
        <?= cell($model->items->os, $color) ?>
        <?= cell($model->items->mac, $color) ?>
    </tr>
    <?php
}
// Окончание таблицы
if ($lastLocation !== 0)
{
    ?>
    </tbody></table>
    <?php
}
