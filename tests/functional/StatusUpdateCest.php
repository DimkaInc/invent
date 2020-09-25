<?php 

class StatusUpdateCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('status/update', [ 'id' => '1' ]);
    }

    // tests
    public function saveData(FunctionalTester $I)
    {
        $header     = Yii::t('status', 'Update Status: {name}', [ 'name' => '' ]);
        $listHeader = Yii::t('status', 'Statuses');
        $name       = '*TEST UPDATE STATUS*';

        $I->see($header, 'h1');
        $I->submitForm('#StatusForm', [
            'Statsus[name]' => $name,
        ]);
        $I->see($listHeader);
    }
}
