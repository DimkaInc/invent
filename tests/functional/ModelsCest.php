<?php 

class ModelsCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('admin'));
        $I->amOnRoute('models/index');
    }

    // Список моделей
    public function openModelsPage(FunctionalTester $I)
    {
        // Заголовок
        $I->see(Yii::t('models', 'Models'), 'h1');
        // Таблица
        $I->see(Yii::t('models', 'Model name'), '#ModelsTable th a');
        $I->see(Yii::t('types', 'Type'), '#ModelsTable th a');
        $I->see(Yii::t('types', 'All types'), '#modelssearch-typename option');
        // Кнопки
        $I->see(Yii::t('models', 'Create Model'), 'a.btn');
    }

    // нажатие кнопки добавить модель
    public function pushCreate(FunctionalTester $I)
    {
        $I->click(Yii::t('models', 'Create Model'), 'a.btn');
        $I->see(Yii::t('models', 'Create Model'), 'h1');
        $I->dontSee(Yii::t('models', 'Models'), 'h1');
    }
}
