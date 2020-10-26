<?php 

class StatusCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('admin'));
        $I->amOnRoute('status/index');
    }

    // tests
    // Наполнение страницы
    public function openPage(FunctionalTester $I)
    {
        $header   = Yii::t('status', 'Statuses');
        $createHeader = Yii::t('status', 'Create status');
        $colName  = Yii::t('status', 'Status name');
        $btCreate = Yii::t('status', 'Create status');
        $btCancel = Yii::t('app', 'Cancel');

        // Заголовок
        $I->see($header, 'h1');
        // Таблица
        $I->see($colName, '#StatusTable th a');
        // Кнопки
        $I->see($btCreate, 'a.btn');
        $I->click($btCreate, 'a.btn');
        $I->see($createHeader, 'h1');
        $I->see($btCancel, 'a.btn');
    }
}
