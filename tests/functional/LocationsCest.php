<?php 

class LocationsCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('admin'));
        $I->amOnRoute('locations/index');
    }

    // tests
    // Проверка открытой страницы
    public function openLocationsPage(\FunctionalTester $I)
    {
        // Заголовок
        $I->see(Yii::t('locations', 'Locations'), 'h1');
        // Таблица
        $I->see(Yii::t('locations', 'Location name'), '#LocationsTable th a');
        $I->see(Yii::t('regions', 'Region name'), '#LocationsTable th a');
        // Кнопки
        $I->see(Yii::t('locations', 'Create location'), 'a.btn');
    }

    // Переход на "Добавить место"
    public function pushCreate(\FunctionalTester $I)
    {
        $I->click(Yii::t('locations', 'Create location'), 'a.btn');
        $I->see(Yii::t('locations', 'Create location'), 'h1');
    }

    // клик по месту
    public function clickUpdate(\FunctionalTester $I)
    {
        $I->click('', '#LocationsTable td a');
        $I->see(Yii::t('locations', 'Update Locations: {name}', [ 'name' => '', ]), 'h1');
    }
}
