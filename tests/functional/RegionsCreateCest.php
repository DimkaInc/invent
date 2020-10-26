<?php 

class RegionsCreateCest
{

    public function _before(FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('admin'));
        $I->amOnRoute('regions/create');
    }

    // tests

    // Запись данных
    public function saveDate(FunctionalTester $I)
    {
        $name       = '*TEST CREATE REGIONS*';
        $listHeader = Yii::t('regions', 'Regions');
        $header     = Yii::t('regions', 'Create region');
        $btCancel   = Yii::t('app', 'Cancel');
        $btSave     = Yii::t('app', 'Save');
        $error      = Yii::t('yii', '{attribute} cannot be blank.', [ 'attribute' => Yii::t('regions', 'Region name') ]);

        $I->see($header, 'h1');
        $I->see($btCancel, 'a.btn');
        $I->click($btSave, 'button');
        $I->see($error);
        $I->submitForm('#RegionsForm', [
            'Regions[name]' => $name,
        ]);
        $I->see($name, '#RegionsTable td a');
        $I->see($listHeader, 'h1');
    }
}
