<?php 

class RegionsCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('regions/index');
    }

    // tests
    // Страница
    public function openPage(\FunctionalTester $I)
    {
        $I->see(Yii::t('regions', 'Regions'), 'h1');
        // Таблица
        $I->see(Yii::t('regions', 'Region'), '#RegionsTable th a');
        // Кнопки
        $I->see(Yii::t('regions', 'Create region'), 'a.btn');
    }

    // переход по кнопке "Добавить регион"
    public function pushCreateRegion(\FunctionalTester $I)
    {
        $I->click(Yii::t('regions', 'Create region'));
        $I->see(Yii::t('regions', 'Create region'), 'h1');
    }
}
