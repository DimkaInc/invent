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

foreach ($models as $model)
{
    $color='#000000';
    if ($model->status->name == 'Списано' || $model->status->name == 'К списанию')
    {
        $color='#FF0000';
    }
    if ($model->status->name == 'Работает')
    {
        $color='#009F00';
    }
    if ($model->status->name == 'Сломано')
    {
        $color='#0000FF';
    }
    if ($model->locations->id != $lastLocation)
    {
        if ($lastLocation == 0)
        {
    ?>
        <table border="1" style="padding: 2px 2px 2px 2px;" width="100%"><tbody>
    <?php
        }
        $lastLocation = $model->locations->id;
    ?>
        <tr>
            <td colspan="8" style="padding: 2px 2px 2px 2px;"><H2><?= $model->locations->regions->name ?></H2>
            <H3><?= $model->locations->name ?></H3></td>
        </tr>
        <tr bgcolor="#EFEFEF">
            <th style= "text-align:center">Инвентарный номер</th>
            <th style= "text-align:center">Серийный номер</th>
            <th style= "text-align:center">Оборудование</th>
            <th style= "text-align:center">Тип</th>
            <th style= "text-align:center">Состояние</th>
            <th style= "text-align:center">Сетевое имя</th>
            <th style= "text-align:center">ОС</th>
            <th style= "text-align:center">МАС адрес</th>
        </tr>
    <?php
    }
    ?>
    <tr>
        <td style="padding: 2px 2px 2px 2px; color:<?= $color ?>"><?= $model->items->invent ?></td>
        <td style="padding: 2px 2px 2px 2px; color:<?= $color ?>"><?= $model->items->serial ?></td>
        <td style="padding: 2px 2px 2px 2px; color:<?= $color ?>"><?= $model->items->models->name ?></td>
        <td style="padding: 2px 2px 2px 2px; color:<?= $color ?>"><?= $model->items->models->types->name ?></td>
        <td nowrap style="padding: 2px 2px 2px 2px; color:<?= $color ?>"><?= $model->status->name ?></td>
        <td nowrap style="padding: 2px 2px 2px 2px; color:<?= $color ?>"><?= $model->items->name ?></td>
        <td style="padding: 2px 2px 2px 2px; color:<?= $color ?>"><?= $model->items->os ?></td>
        <td style="padding: 2px 2px 2px 2px; color:<?= $color ?>"><?= $model->items->mac ?></td>
    </tr>
    <?php
}
if ($lastLocation !== 0)
{
?>
        </tbody></table>

<?php
}
?>
<div>

</div>
