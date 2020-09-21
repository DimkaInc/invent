<?php

class ContactFormCest 
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage(['site/contact']);
    }

    public function openContactPage(\FunctionalTester $I)
    {
        $I->see(Yii::t('contact', 'Contact'), 'h1');
    }

    public function submitEmptyForm(\FunctionalTester $I)
    {
        $I->submitForm('#contact-form', []);
        $I->expectTo('see validations errors');
        $I->see(Yii::t('contact', 'Contact'), 'h1');
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => Yii::t('contact', 'Name')]));
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => Yii::t('contact', 'Email')]));
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => Yii::t('contact', 'Subject')]));
        $I->see(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => Yii::t('contact', 'Body')]));
        $I->see(Yii::t('yii', 'The verification code is incorrect.'));
    }

    public function submitFormWithIncorrectEmail(\FunctionalTester $I)
    {
        $I->submitForm('#contact-form', [
            'ContactForm[name]' => 'tester',
            'ContactForm[email]' => 'tester.email',
            'ContactForm[subject]' => 'test subject',
            'ContactForm[body]' => 'test content',
            'ContactForm[verifyCode]' => 'testme',
        ]);
        $I->expectTo('see that email address is wrong');
        $I->dontSee('Name cannot be blank', '.help-inline');
        $I->see(Yii::t('yii', '{attribute} is not a valid email address.', ['attribute' => Yii::t('contact', 'Email')]));
        $I->dontSee('Subject cannot be blank', '.help-inline');
        $I->dontSee('Body cannot be blank', '.help-inline');
        $I->dontSee('The verification code is incorrect', '.help-inline');
    }

    public function submitFormSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#contact-form', [
            'ContactForm[name]' => 'tester',
            'ContactForm[email]' => 'tester@example.com',
            'ContactForm[subject]' => 'test subject',
            'ContactForm[body]' => 'test content',
            'ContactForm[verifyCode]' => 'testme',
        ]);
        $I->seeEmailIsSent();
        $I->dontSeeElement('#contact-form');
        $I->see(Yii::t('contact', 'Thank you for contacting us. We will respond to you as soon as possible.'));
    }
}
