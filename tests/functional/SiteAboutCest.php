<?php

class SiteAboutCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage(['site/about']);
    }

    public function openAboutPage(\FunctionalTester $I)
    {
        $I->see(Yii::t('app', 'About'), 'h1');
        $I->see(Yii::$app->version);
    }
}
