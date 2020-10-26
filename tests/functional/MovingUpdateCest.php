<?php 

use Codeception\Util\Locator;

class MovingUpdateCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('admin'));
        $I->amOnRoute('items/update', [ 'id' => '1' ]);
        $I->see(Yii::t('items', 'Update Items: {name}', [ 'name' => '' ]), 'h1' );
        $I->click('', '#MovingTable td a');
        $I->dontSee(Yii::t('items', 'Update Items: {name}', [ 'name' => '', ]), 'h1');
    }

    // tests
    // Изменение данных
    public function tryToTest(FunctionalTester $I)
    {
        $I->see(Yii::t('moving', 'Update Moving: {name}', [ 'name' => '', ]), 'h1');
        $I->fillField('Moving[date]', '01.01.2000');
        $I->selectOption('Moving[state_id]', 'Склад');
        $state_id = '1'; // id Склад
        $I->selectOption('Moving[location_id]', 'Бородино (Можайская ветеринарная станция)');
        $location_id = 14; // id Бородино (Можайская ветеринарная станция)
        $I->fillField('Moving[comment]', '*TEST COMMENT UPDATE*');
        $I->click(Yii::t('app', 'Save'), 'button');
        $I->see(Yii::t('items', 'Update Items: {name}', [ 'name' => '', ]), 'h1');
        $I->see('01.01.2000', '#MovingTable td a');
    }
}
