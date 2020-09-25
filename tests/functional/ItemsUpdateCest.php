<?php

use Codeception\Util\Locator;

class ItemsUpdateCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('items/update', [ 'id' => '1' ]);
    }

    // tests

    // Наполнение страницы
    public function openUpdatePage(\FunctionalTester $I)
    {
        // Заголовок
        $I->see(Yii::t('items', 'Update Items: {name}', [ 'name' => '', ]), 'h1');
        // Поля
        $I->see(Yii::t('items', 'Item type'), 'label');
        $I->see(Yii::t('items', 'Item network name'), 'label');
        $I->see(Yii::t('items', 'Model'), 'label');
        $I->see(Yii::t('items', 'Operating system'), 'label');
        $I->see(Yii::t('items', 'MAC address'), 'label');
        $I->see(Yii::t('items', 'Serial number'), 'label');
        $I->see(Yii::t('items', 'Product number'), 'label');
        $I->see(Yii::t('items', 'Model number'), 'label');
        $I->see(Yii::t('items', 'Inventory number'), 'label');
        $I->see(Yii::t('items', 'Additional Information'), 'label');
        // Кнопки
        $I->see(Yii::t('app', 'Save'), 'button');
        $I->see(Yii::t('app', 'Cancel'), 'a.btn');
        // Заголовок таблицы
        $I->see(Yii::t('moving', 'Movings'), 'h3');
        // Поля таблицы
        $I->see(Yii::t('moving', 'Moving date'), '#MovingTable th a');
        $I->see(Yii::t('items', 'Model'), '#MovingTable th a');
        $I->see(Yii::t('locations', 'Locations'), '#MovingTable th a');
        $I->see(Yii::t('status', 'Status'), '#MovingTable th a');

    }

    // Заполнение формы
    public function enterData(\FunctionalTester $I)
    {
        // Заполнение полей и отправка
        $I->fillField('Items[name]', '*TEST CHANGE NAME*');
        $I->click(Yii::t('app', 'Save'));
        // Проверка перехода
        $I->see(Yii::t('items', 'Items'), 'h1');
        $I->dontSee(Yii::t('items', 'Update Items: {name}', [ 'name' => '', ]), 'h1');
    }

    // Нажатие на "Отменить"
    public function pushCancel(\FunctionalTester $I)
    {
        $I->click(Yii::t('app', 'Cancel'), 'a.btn');
        $I->see(Yii::t('items', 'Items'), 'h1');
        $I->dontSee(Yii::t('items', 'Update Items: {name}', [ 'name' => '', ]), 'h1');
    }

    // Нажатие на кнопку "Типы"
    public function pushTypes(\FunctionalTester $I)
    {
        $I->click(Yii::t('types', 'Types'), 'a.btn');
        $I->see(Yii::t('types', 'Types'), 'h1');
        $I->dontSee(Yii::t('items', 'Update Items: {name}', [ 'name' => '', ]), 'h1');
    }

    // Нажатие на кнопку "Добавить перемещение"
    public function pushAddMoving(\FunctionalTester $I)
    {
        $I->click(Yii::t('moving', 'Create Moving'), 'a.btn');
        $I->see(Yii::t('moving', 'Create Moving'), 'h1');
        $I->dontSee(Yii::t('items', 'Update Items: {name}', [ 'name' => '', ]), 'h1');
    }

    // переход на редактирование перемещения
    public function clickRowMoving(\FunctionalTester $I)
    {
        $I->click('', '#MovingTable td a');
        $I->see(Yii::t('moving', 'Update Moving: {name}', [ 'name' => '', ]), 'h1');
        $I->dontSee(Yii::t('items', 'Update Items: {name}', [ 'name' => '', ]), 'h1');
    }
}
