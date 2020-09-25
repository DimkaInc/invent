<?php 

class TypesCreateCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('types/create');
    }

    // tests
    public function saveData(FunctionalTester $I)
    {
        $header = Yii::t('types', 'Create type');
        $listHeader = Yii::t('types', 'Types');
        $name = '*TEST CREATE TYPE*';
        $btSave = Yii::t('app', 'Save');
        
        $I->see($header, 'h1');
        $I->fillField('Types[name]', $name);
        $I->click($btSave);
        $I->see($listHeader);
    }
}
