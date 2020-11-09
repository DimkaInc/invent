<?php

use Codeception\Util\Locator;
use app\models\User;

class ItemsCreateCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedInAs(User::findByUsername('user'));
        $I->amOnRoute('items/create');
    }

    // tests
    // Открыта страница добавления
    public function openCreatePage(FunctionalTester $I)
    {
        // Заголовок
        $I->see(Yii::t('items', 'Create Items'), 'h1');
        // Поля
        $I->see(Yii::t('items', 'Item network name'), 'label');
        $I->see(Yii::t('items', 'Model'), 'label');
        $I->see(Yii::t('items', 'Operating system'), 'label');
        $I->see(Yii::t('items', 'MAC address'), 'label');
        $I->see(Yii::t('items', 'Serial number'), 'label');
        $I->see(Yii::t('items', 'Inventory number'), 'label');
        $I->see(Yii::t('items', 'Additional Information'), 'label');
        $I->see(Yii::t('moving', 'Moving date'), 'label');
        $I->see(Yii::t('status', 'Status'), 'label');
        $I->see(Yii::t('locations', 'Location'), 'label');
        // Кнопки
        $I->see(Yii::t('app', 'Save'), 'button');
        $I->see(Yii::t('app', 'Cancel'), 'a.btn');
    }

    // Заполнение формы
    public function enterData(FunctionalTester $I)
    {
        $I->submitForm('#ItemForm', [
            'Items[name]'         => 'TEST-WS-0001',
            'Items[model_id]'     => 1,
            'Items[os]'           => 'Тестовая ОС',
            'Items[mac]'          => '00:00-MAC-:00:00',
            'Items[serial]'       => '*TEST SERIAL NUMBER*',
            'Items[invent]'       => '*TEST INVENTORY NUMBER*',
            'Items[comment]'      => 'Тестирование. Это тестовая запись.',
            'Moving[date]'        => '01.01.2020',
            'Moving[state_id]'    => '1',
            'Moving[location_id]' => '1',
        ]);
        $I->see(Yii::t('items', 'Items'), 'h1');
        $I->dontSee(Yii::t('items', 'Create Items'), 'h1');
    }

    // Нажатие на кнопку "Типы"
    public function pushTypes(FunctionalTester $I)
    {
        $I->click(Locator::contains('div a', Yii::t('models', 'Models')));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->see(Yii::t('models', 'Models'), 'h1');
        $I->dontSee(Yii::t('items', 'Create Items'), 'h1');
    }

    // Нажатие на кнопку "Места размещения"
    public function pushLocations(FunctionalTester $I)
    {
        $I->click(Locator::contains('div a', Yii::t('locations', 'Locations')));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->see(Yii::t('locations', 'Locations'), 'h1');
        $I->dontSee(Yii::t('items', 'Create Items'), 'h1');
    }

    // Нажатие на кнопку "Отмена"
    public function pushCancel(FunctionalTester $I)
    {
        $I->click(Locator::contains('div a', Yii::t('app', 'Cancel')));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->see(Yii::t('items', 'Items'), 'h1');
        $I->dontSee(Yii::t('items', 'Create Items'), 'h1');
    }
}
