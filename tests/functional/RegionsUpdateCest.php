<?php 

class RegionsUpdateCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('regions/update', [ 'id' => '1' ]);
    }

    // tests
    public function updateData(\FunctionalTester $I)
    {
        $header = Yii::t('regions', 'Update Regions: {name}', [ 'name' => '' ]);
        $listHeader = Yii::t('regions', 'Regions');
        $name = '*TEST UPDATE REGION*';
        $button = Yii::t('app', 'Save');

        $I->see($title, 'h1');
        $I->fillField('Regions[name]', $name );
        $I->click($button);
        $I->see($listHeader, 'h1');
    }
}
