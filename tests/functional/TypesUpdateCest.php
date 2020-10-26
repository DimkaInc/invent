<?php 

class TypesUpdateCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('admin'));
        $I->amOnRoute('types/update', [ 'id' => '1' ]);
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        $header = Yii::t('types', 'Update Types: {name}', [ 'name' => '' ]);
        $listHeader = Yii::t('types', 'Types');
        $name = '*TEST UPDATE TYPE*';
        $btSave = Yii::t('types', 'Types');

        $I->see($header, 'h1');
        $I->fillField('Types[name]', $name);
        $I->click($btSave);
        $I->see($listHeader, 'h1');
    }
}
