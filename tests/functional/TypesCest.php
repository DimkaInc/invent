<?php 

class TypesCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('types/index');
    }

    // tests
    public function openPage(FunctionalTester $I)
    {
        $header = Yii::t('types', 'Types');
        $createHeader = Yii::t('types', 'Create type');
        $colName = Yii::t('types', 'Type');
        $btCreate = Yii::t('types', 'Create type');
        $btCancel = Yii::t('app', 'Cancel');
        
        // Заголовок
        $I->see($header, 'h1');
        // Таблица
        $I->see($colName, '#TypesTable th a');
        // Кнопка
        $I->see($btCreate, 'a.btn');
        $I->click($btCreate, 'a.btn');
        $I->see($createHeader, 'h1');
        $I->click($btCancel, 'a.btn');
        $I->see($header, 'h1');
    }
}
