<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use app\models\User;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerLinkTag([ 'rel' => 'icon', 'type' => 'image/x-icon', 'href' => Url::base() . '/favicon.ico' ]) ?>
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::t('app', Yii::$app->name),
        'brandUrl'   => Yii::$app->homeUrl,
        'options'    => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $items = [];
    array_push($items, [ 'label' => Yii::t('app', 'Home'   ), 'url' => [ '/site/index' ]] );
    array_push($items, [ 'label' => Yii::t('app', 'About'  ), 'url' => [ '/site/about' ]] );
    array_push($items, [ 'label' => Yii::t('app', 'Contact'), 'url' => [ '/site/contact' ]] );
    if (User::canPermission('createRecord'))
    {
        array_push($items, [ 'label' => Yii::t('app', 'Items' ), 'url' => [ '/items/index' ]] );
    }
    if (User::canPermission('updateRecord'))
    {
        array_push($items, [ 'label' => Yii::t('users', 'Manage users'), 'options' => [ 'id' => 'down_history' ], 'items' => [
                [ 'label' => Yii::t('users', 'Change password'), 'url' => [ 'site/changepassword' ]],
                [ 'label' => Yii::t('users', 'Create users'), 'url' => [ 'site/createusers' ]],
            ]]);
    }
    elseif (User::canPermission('createRecord'))
    {
        array_push($items, [ 'label' => Yii::t('users', 'Change password'), 'url' => [ 'site/changepassword' ]]);
    
    }
    array_push($items, Yii::$app->user->isGuest ? (
                [ 'label' => Yii::t('app', 'Login'), 'url' => [ '/site/login' ]]
            ) : (
                '<li>'
//                . Html::a(Yii::t('users', 'Change password'), Url::to([ 'site/changepassword' ]), [ 'class' => 'btn btn-link' ])
//                . '</li><li>'
                . Html::beginForm([ '/site/logout' ], 'post')
                . Html::submitButton(
                    Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                    [ 'class' => 'btn btn-link logout' ]
                )
                . Html::endForm()
                . '</li>'
            ) );

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items'   => $items,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params[ 'breadcrumbs' ] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <a href="mailto:dimkainc@mail.ru"><?= Yii::t('app', 'Dmitry Dobryshin') ?></a> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
