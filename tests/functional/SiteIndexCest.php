<?php

use Codeception\Util\Locator;

class SiteIndexCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage(['site/index']);
    }

    // Проверка надписей
    public function openIndexPage(\FunctionalTester $I)
    {
        // Заголовок
        $I->seeInTitle(Yii::t('app', 'Inventory'));
        // Меню
        $I->see(Yii::t('app', 'Inventory'), 'a.navbar-brand');
        $I->see(Yii::t('app', 'Home'), '.nav li.active a');
        $I->see(Yii::t('app', 'About'), '.nav li a');
        $I->see(Yii::t('app', 'Contact'), '.nav li a');
        //$I->see(Yii::t('app', 'Items'), '.nav li a');
        $I->see(Yii::t('app', 'Login'), '.nav li a');
        // Содержимое сайта
        $I->see(Yii::t('app', 'Inventory'), 'a');
        // Первая таблица
        $I->see(Yii::t('app', 'Number of equipment by regions'), 'h2');
        $I->see(Yii::t('regions', 'Region'), '#RegionsTable tr th a');
        $I->see(Yii::t('items', 'Total items count'), '#RegionsTable tr th');
        $I->see(Yii::t('items', 'Total items checked'), '#RegionsTable tr th');
        // Вторая таблица
        $I->see(Yii::t('app', 'Number of items by type'), 'h2');
        $I->see(Yii::t('types', 'Type'), '#TypesTable tr th a');
        $I->see(Yii::t('items', 'Total items count'), '#TypesTable tr  th');
        $I->see(Yii::t('items', 'Total items checked'), '#TypesTable tr th');
        // Кнопка
        //$I->see(Yii::t('app', 'Inventory'), 'a.btn');
    }

    // Проверка перехода на страницу учёта
    public function moveToCheckInventory(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('admin'));
        $I->amOnPage('/');
        $I->click(Yii::t('app', 'Inventory'), 'a.btn');
        $I->see(Yii::t('items', 'Inventory QR-code'));
    }

    // Проверка перехода на страницу Описание
    public function moveToAbout(\FunctionalTester $I)
    {
        $I->click(Yii::t('app', 'About'), 'a');
        $I->dontSee(Yii::t('app', 'Home'), '.nav li.active a');
        $I->see(Yii::t('app', 'About'), '.nav li.active a');
        $I->see(Yii::t('app', 'About'), 'h1');
        $I->dontSee(Yii::t('app', 'Number of equipment by regions'), 'h2');
    }

    // Проверка перехода на страницу Связаться
    public function moveToContact(\FunctionalTester $I)
    {
        $I->click(Yii::t('app', 'Contact'), 'a');
        $I->dontSee(Yii::t('app', 'Home'), '.nav li.active a');
        $I->see(Yii::t('app', 'Contact'), '.nav li.active a');
        $I->see(Yii::t('contact', 'Contact'), 'h1');
        $I->dontSee(Yii::t('app', 'Number of equipment by regions'), 'h2');
    }

    // Проверка перехода на страницу Items
    public function moveToItems(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('user'));
        $I->amOnPage('/');
        $I->click(Yii::t('app', 'Items'), 'a');
        $I->dontSee(Yii::t('app', 'Home'), '.nav li.active a');
        $I->see(Yii::t('app', 'Items'), '.nav li.active a');
        $I->see(Yii::t('items', 'Items'), 'h1');
        $I->dontSee(Yii::t('app', 'Number of equipment by regions'), 'h2');
    }

    // Проверка перехода на страницу Login
    public function moveToLogin(\FunctionalTester $I)
    {
        $I->click(Yii::t('app', 'Login'), 'a');
        $I->dontSee(Yii::t('app', 'Home'), '.nav li.active a');
        $I->see(Yii::t('app', 'Login'), '.nav li.active a');
        $I->see(Yii::t('app', 'Login'), 'h1');
        $I->dontSee(Yii::t('app', 'Number of equipment by regions'), 'h2');
    }

}
