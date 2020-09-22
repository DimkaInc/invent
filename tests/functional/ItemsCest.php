<?php

use Codeception\Util\Locator;

class ItemsCest
{
    // Страница Items
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage(['items/index']);
    }

    // Есть заголовок
    public function openItemsPage(\FunctionalTester $I)
    {
        $I->see(Yii::t('items', 'Items'), 'h1');
    }

    // Переход к странице добавления
    public function pushCreateItem(\FunctionalTester $I)
    {
        $I->click(Locator::contains('div a', Yii::t('items', 'Create Items')));
        $I->see(Yii::t('items', 'Create Items'), 'h1');
    }

    // Переход к странице импорта
    public function pushImportItems(\FunctionalTester $I)
    {
        $I->click(Locator::contains('div a', Yii::t('items', 'Import')));
        $I->see(Yii::t('items', 'Import Items'), 'h1');
    }

    // Формирование печатной формы PDF
    public function moveToPrintItemsCheck(\FunctionalTester $I)
    {
        $I->click(Locator::contains('div a', Yii::t('items', 'Print Items')));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);

        // Этот участок кода пока не понятно как включить, но функционал обязательно добавлю.
        // Точно .pdf
        //$I->canSeeHttpHeader('Content-type', 'application/pdf');
        //$I->canSeeHttpHeader('Content-disposition', 'attachment; filename="' . Yii::t('app', Yii::$app->name) . ' (' . Yii::t('items', 'Items') . ').pdf"');
        $I->see('%PDF');

        // Не пустой документ
        //$I->canSeeHttpHeader('Content-length', '1024');
        // или
        //$actualLength= $I->grabHttpHeader('Content-length');
        //$I->assertGreaterThan(1024, $actualLength);
    }

    // Начало инвентаризации
    public function startInventoryCheck(\FunctionalTester $I)
    {
        $I->click(Locator::contains('div a', Yii::t('items', 'Start checking')));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->see('', 'tr.danger');
    }

    //* // Нажатие на кнопку "Удалить"
    public function deleteItemCheck(\FunctionalTester $I)
    {
        $I->click(Locator::find('a', [ 'title' => Yii::t('yii', 'Delete') ]));
        $I->seeResponseCodeIs(405);
        //$I->see(Yii::t('yii', 'Are you sure you want to delete this item?'));
    } // */

    // Печать отдельной наклейки
    public function printItemCheck(\FunctionalTester $I)
    {
        $I->click(Locator::find('a', [ 'title' => Yii::t('items', 'Print selected labels') ]));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->see('%PDF');
    }

    // переход к редактированию элемента
    public function clickItemCheck(\FunctionalTester $I)
    {
        $I->click(Locator::contains('tr td a', ''));
        $I->see(Yii::t('items', 'Update Items: {name}', [ 'name' => '', ]), 'h1');
    }
}
