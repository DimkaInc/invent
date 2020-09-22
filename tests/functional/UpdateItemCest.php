<?php

use Codeception\Util\Locator;

class UpdateItemCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('items/Update');
    }

    // tests
    // Открыта страница добавления
    public function openCreatePage(\FunctionalTester $I)
    {
        $I->amOnRoute('items/update', [ 'id' => '1' ]);
        $I->see(Yii::t('items', 'Update Items: {name}', ['name' => '',]), 'h1');
    }

    // Заполнение формы
    public function enterData(\FunctionalTester $I)
    {
        $I->amOnRoute('items/update', ['id' => '1']);
        $I->submitForm('#ItemForm', []);
        $I->see(Yii::t('items', 'Items'), 'h1');
        $I->dontSee(Yii::t('items', 'Update Items: {name}', ['name' => '',]), 'h1');
    }
    
    // Нажатие на кнопку "Типы"
    public function pushTypes(\FunctionalTester $I)
    {
        $I->amOnRoute('items/update', ['id' => '1']);
        $I->click(Locator::contains('div a', Yii::t('types', 'Types')));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->see(Yii::t('types', 'Types'), 'h1');
    }

}
