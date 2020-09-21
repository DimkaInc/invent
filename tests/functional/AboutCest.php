<?php

class AboutCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage(['site/about']);
    }

    public function openAboutPage(\FunctionalTester $I)
    {
        $I->see(Yii::$app->version);
    }
}
