<?php

use yii\helpers\Html;

function showUrlUpdate($name, $data)
{
    return Html::a(Html::encode($name),
        ['update',
            'id' => $data->id,
        ]);
}

function writeLog($logline)
{
    $fp = fopen('data.log', 'a');
    fwrite($fp, $logline . "\n");
    fclose($fp);
}

?>