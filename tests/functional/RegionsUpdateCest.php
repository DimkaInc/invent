<?php 

class RegionsUpdateCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('admin'));
        $I->amOnRoute('regions/update', [ 'id' => '1' ]);
    }

    // tests
    public function updateData(\FunctionalTester $I)
    {
        $title = Yii::t('regions', 'Update Regions: {name}', [ 'name' => '' ]);
        $listHeader = Yii::t('regions', 'Regions');
        $name = '*TEST UPDATE REGION*';
        $button = Yii::t('app', 'Save');

        $I->see($title, 'h1');
        $I->fillField('Regions[name]', $name );
        $I->click($button);
        $I->see($listHeader, 'h1');
    }
}
