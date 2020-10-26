<?php 

class LocationsUpdateCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('admin'));
        $I->amOnRoute('locations/update', [ 'id' => '1' ]);
    }

    // tests
    // проверка страницы
    public function openPage(\FunctionalTester $I)
    {
        // заголовок
        $I->see(Yii::t('locations', 'Update Locations: {name}', [ 'name' => '', ]), 'h1');
        // Поля
        $I->see(Yii::t('regions', 'Region'), 'label');
        $I->see(Yii::t('locations', 'Location name'), 'label');
        // Кнопки
        $I->see(Yii::t('regions', 'Regions'), 'a.btn');
        $I->see(Yii::t('app', 'Save'), 'button');
        $I->see(Yii::t('app', 'Cancel'), 'a.btn');
    }

    // переход по кнопке
    public function pushRegions(\FunctionalTester $I)
    {
        $I->click(Yii::t('regions', 'Regions'), 'a.btn');
        $I->see(Yii::t('regions', 'Regions'), 'h1');
        $I->dontSee(Yii::t('locations', 'Update Locations: {name}', [ 'name' => '', ]), 'h1');
    }

    // изменение данных
    public function updateData(\FunctionalTester $I)
    {
        $I->selectOption('Locations[region_id]', 'Истринская ветеринарная станция');
        $region_id = '4';
        $I->fillField('Locations[name]', '*TEST LOCATION*');
        $I->click(Yii::t('app', 'Save'));
        $I->see(Yii::t('locations', 'Locations'), 'h1');
    }
}
