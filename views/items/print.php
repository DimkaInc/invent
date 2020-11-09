<?php

use yii\helpers\Html;
use app\models\Items;
use app\models\Types;
use app\models\User;

use xj\qrcode\QRcode;
use xj\qrcode\widgets\Email;
use xj\qrcode\widgets\Text;

if (! User::canPermission('updateRecord'))
{
    return $this->redirect(['site/index']);
}

$this->title = Yii::t('items', 'Items');
$this->params[ 'breadcrumbs' ][] = $this->title;

?>
<div class="items-index">

    <table width="100%">
        <tbody>
            <?php
                $counts = 3;
                $percent = (100 / $counts) % 100;
                $ind = 0;
                foreach ($models as $model )
                {
            ?>

                <?php
                    if ($ind % $counts == 0)
                    {
                        echo '<tr style="height:120px;min-height:120px;max-height:120px;">';
                    }
                    $ind++;
                ?>
                <td style="width:<?= $percent ?>%;">
                    <div style="margin:2px 2px 2px 2px;border-color:black;border-width:4,4;border-style:double;border-radius:15px;height:94%;width:98%">
                        <table width="100%" height="100%">
                            <tbody>
                                <tr>
                                    <td style="vertical-align:middle;">
                                        <?= Text::widget([
                                            'outputDir'    => '@webroot/upload/qrcode',
                                            'outputDirWeb' => '@web/upload/qrcode',
                                            'text'         => $model->invent . ', ' . $model->serial,
                                            'size'         => 3,
                                            'margin'       => 4,
                                            'ecLevel'      => QRcode::QR_ECLEVEL_L,
                                        ]) ?>
                                    </td>
                                    <td style="vertical-align:middle;">
                                        <?= $model->invent . ', ' . $model->modelName ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
                <?php
                    if ($ind % $counts == 0)
                    {
                        echo '</tr>';
                    }
                }
                if ($ind % $counts != 0)
                {
                    while ($ind % $counts != 0)
                    {
                        echo '<td>&nbsp;</td>';
                        $ind++;
                    }
                    echo '</tr>';
                }
            ?>
        </tbody>
    </table>
</div>
