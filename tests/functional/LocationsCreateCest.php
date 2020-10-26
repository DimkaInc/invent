<?php 

class LocationsCreateCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('user'));
        $I->amOnRoute('locations/create');
    }

    // tests
    public function enterData(FunctionalTester $I)
    {
        $I->see(Yii::t('locations', 'Create location'), 'h1');
        $region_id = '1';
        $I->selectOption('Locations[region_id]', 'Одинцовская ветеринарная станция');
        $I->fillField('Locations[name]', '*TEST INSERT LOCATION*');
        $I->click(Yii::t('app', 'Save'));
        $I->see(Yii::t('locations', 'Locations'));
    }
}
