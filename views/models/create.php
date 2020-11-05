<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Models */

$this->title = Yii::t('models', 'Create Model');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Models'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="models-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
