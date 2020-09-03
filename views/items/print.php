<?php

use yii\helpers\Html;
use app\models\Items;
use app\models\Types;

use xj\qrcode\QRcode;
use xj\qrcode\widgets\Email;
use xj\qrcode\widgets\Text;

$this->title = Yii::t('items', 'Items');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="items-index">

    <!-- h1 style="font-family:Helvetica"><?= Html::encode($this->title) ?></h1 -->
    <table width="100%" border="2">
        <tbody>
            <?php
                $counts = 3;
                $ind = 0;
                foreach ($models as $model ) {
            ?>

                <?php
                    if ($ind % $counts == 0) {
                        echo '<tr style="height:120px;min-height:120px;max-height:120px;">';
                    }
                    $ind++;
                ?>
                <td>
                    <div style="margin:2px;border-color:black;border-width:4,4;border-style:solid;border-radius:15px;height:95%;width:100%">
                        <table width="100%" height="100%">
                            <tbody>
                                <tr>
                                    <td style="vertical-align:middle;">
                                        <?= Text::widget([
                                            'outputDir' => '@webroot/upload/qrcode',
                                            'outputDirWeb' => '@web/upload/qrcode',
                                            'text' => $model->invent . ', ' . $model->serial,
                                            'size' => 3,
                                            'margin' => 4,
                                            'ecLevel' => QRcode::QR_ECLEVEL_L,
                                        ]) ?>
                                    </td>
                                    <td style="vertical-align:middle;">
                                        <?= $model->invent . ', ' . $model->model ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
            <?php
                if ($ind % $counts == 0) {
                    echo '</tr>';
                }
            ?>
        <?php
            }
            if ($ind % $counts != 0) {
                echo '</tr>';
            }
        ?>
        </tbody>
    </table>
</div>
