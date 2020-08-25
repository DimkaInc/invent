<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::t('app', 'About');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <div>
        Программа инвентаризации. <sub style="color:gray;">(Версия <?= Yii::$app->version ?>)</sub>
    </div>
    <div>Разработа для ведения учёта оборудования по подразделениям.</div>
    <div style="color:lightgray">По вопросам работы программы, обращайтесь к разработчику.</div>

</div>
