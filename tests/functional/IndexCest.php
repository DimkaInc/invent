<?php

class IndexCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage(['site/index']);
    }

    public function openIndexPage(\FunctionalTester $I)
    {
        $I->see(Yii::t('app', 'Inventory'), 'a');
    }

    public function checkFirstHeader(\FunctionalTester $I)
    {
        $I->see(Yii::t('app', 'Number of equipment by regions'));
    }

    public function checkSecondHeader(\FunctionalTester $I)
    {
        $I->see(Yii::t('app', 'Number of items by type'));
    }
}
