<?php 

class StatusCreateCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('status/create');
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        $header     = Yii::t('status', 'Create status');
        $listHeader = Yii::t('status', 'Statuses');
        $name       = '*TEST CREATE STATUS*';
        $btSave     = Yii::t('app', 'Save');
        $btCancel   = Yii::t('app', 'Cancel');

        $I->see($header, 'h1');
        $I->see($btCancel, 'a.btn');
        $I->fillField('Status[name]', $name);
        $I->click($btSave);
        $I->see($listHeader, 'h1');
    }
}
