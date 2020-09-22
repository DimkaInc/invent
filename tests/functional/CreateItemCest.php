<?php

use Codeception\Util\Locator;

class CreateItemCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('items/create');
    }

    // tests
    // Открыта страница добавления
    public function openCreatePage(\FunctionalTester $I)
    {
        $I->amOnPage([ 'items/create' ]);
        $I->see(Yii::t('items', 'Create Items'), 'h1');
    }

    // Заполнение формы
    public function enterData(\FunctionalTester $I)
    {
        $I->submitForm('#ItemForm', [
            'Items[type_id]'      => '1',
            'Items[name]'         => 'TEST-WS-0001',
            'Items[model]'        => 'Тестовый компьютер',
            'Items[os]'           => 'Тестовая ОС',
            'Items[mac]'          => '00:00-MAC-:00:00',
            'Items[serial]'       => '*TEST SERIAL NUMBER*',
            'Items[product]'      => 'TEST PRODUCT',
            'Items[modelnumber]'  => 'TEST MODEL NUMBER',
            'Items[invent]'       => '*TEST INVENTORY NUMBER*',
            'Items[comment]'      => 'Тестирование. Это тестовая запись.',
            'Moving[date]'        => '01.01.2020',
            'Moving[state_id]'    => '1',
            'Moving[location_id]' => '1',
        ]);
        $I->see(Yii::t('items', 'Items'), 'h1');
        $I->dontSee(Yii::t('items', 'Create Items'), 'h1');
        $I->haveInDatabase('items', ['name' => 'TEST-WS-0001', 'model' => 'Тестовый компьютер', 'serial' => '*TEST SERIAL NUMBER*', 'invent' => '*TEST INVENTORY NUMBER*']);
    }
    
    // Нажатие на кнопку "Типы"
    public function pushTypes(\FunctionalTester $I)
    {
        $I->click(Locator::contains('div a', Yii::t('types', 'Types')));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->see(Yii::t('types', 'Types'), 'h1');
    }

    // Нажатие на кнопку "Места размещения"
    public function pushLocations(\FunctionalTester $I)
    {
        $I->click(Locator::contains('div a', Yii::t('locations', 'Locations')));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->see(Yii::t('locations', 'Locations'), 'h1');
    }

}
