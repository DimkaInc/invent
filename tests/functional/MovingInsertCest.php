<?php

use Codeception\Util\Locator;

class MovingInsertCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('admin'));
        $I->amOnRoute('items/update', ['id' => '1']);
        $I->click(Yii::t('moving', 'Create Moving'), 'a.btn');
     }

    // tests
    // Проверка открытия страницы на добавление
    public function openInsertPage(\FunctionalTester $I)
    {
        // Заголовок
        $I->see(Yii::t('moving', 'Create Moving'), 'h1');
        // Поля
        $I->dontSee(Yii::t('items', 'Update Items: {name}', [ 'name' => '', ]), 'h1');
        $I->see(Yii::t('moving', 'Moving date'), 'label');
        $I->see(Yii::t('status', 'Status'), 'label');
        $I->see(Yii::t('locations', 'Location'), 'label');
        $I->see(Yii::t('moving', 'Comment'), 'label');
        // Кнопки
        $I->see(Yii::t('app', 'Save'), 'button');
        $I->see(Yii::t('app', 'Cancel'), 'a.btn');
    }

    // Нажатие кнопки "Места размещения"
    public function clickLocations(\FunctionalTester $I)
    {
        $I->click(Locator::contains('div a', Yii::t('locations', 'Locations')));
        $I->see(Yii::t('locations', 'Locations'), 'h1');
        $I->dontSee(Yii::t('moving', 'Create Moving'), 'h1');
    }

    // Нажатие сохранение данных
    public function enterData(\FunctionalTester $I)
    {
        $I->submitForm('#MovingForm', [
            'Moving[date]'        => '20.09.2020',
            'Moving[state_id]'    => '1',
            'Moving[location_id]' => '1',
            'Moving[comment]'     => '*TEST MOVE COMMENT*',
        ]);
        $I->see(Yii::t('items', 'Update Items: {name}', [ 'name' => '', ]), 'h1');
        $I->dontSee(Yii::t('moving', 'Create Moving'), 'h1');
        $I->see('20.09.2020', '#MovingTable a');
    }

    // Нажатие кнопки попытка сохранить неправильную дату
    public function enterWrongAboveTodayData(\FunctionalTester $I)
    {
        $I->submitForm('#MovingForm', [
            'Moving[date]'        => '20.09.2200',
            'Moving[state_id]'    => '1',
            'Moving[location_id]' => '1',
            'Moving[comment]'     => '*TEST MOVE COMMENT*',
        ]);
        $I->dontSee(Yii::t('items', 'Update Items: {name}', [ 'name' => '', ]), 'h1');
        $I->see(Yii::t('moving', 'Create Moving'), 'h1');
        $I->see(Yii::t('moving', 'The date cannot be more than today'));
    }

    // Нажатие кнопки попытка сохранить неправильную дату
    public function enterWrongDataBeforeFirst(\FunctionalTester $I)
    {
        $I->submitForm('#MovingForm', [
            'Moving[date]'        => '01.01.2010',
            'Moving[state_id]'    => '1',
            'Moving[location_id]' => '1',
            'Moving[comment]'     => '*TEST MOVE COMMENT*',
        ]);
        $I->dontSee(Yii::t('items', 'Update Items: {name}', [ 'name' => '', ]), 'h1');
        $I->see(Yii::t('moving', 'Create Moving'), 'h1');
        $I->see(Yii::t('moving', 'Дата не может быть меньше, чем {date}', [ 'date' => '' ]));
    }

}
