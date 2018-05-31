<?php

use Codeception\Step\Argument\PasswordArgument;

class PostCommentCest
{
    const SUBMIT_LOGIN_FORM_BUTTON = 'button[type=submit]';
    const ACTIVITY_LINK = '(//a[contains(@class,"card-btn overlay")])[1]';
    const SUBMIT_COMMENT_BUTTON = '//button[contains(text(),"Siųsti atsiliepimą")]';
    const COMMENT_TEXT = 'Komentaro tekstas';

    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->am('Registered user');
        $I->amOnPage('/');
        $I->click('Prisijungti');
        $I->fillField('login[_username]', 'petrasvalaitis@email.com');
        $I->fillField('login[_password]', new PasswordArgument('password'));
        $I->click(self::SUBMIT_LOGIN_FORM_BUTTON);
        $I->waitForText('Ieškoti būrelio', 5);
        $I->wait(2);
        $I->click(self::ACTIVITY_LINK);
        $I->click('#review-tab');
        $I->wait(2);
        $I->canSee('Komentarai');
        $I->fillField('comment[commentText]', self::COMMENT_TEXT);
        $I->scrollTo(self::SUBMIT_COMMENT_BUTTON);
        $I->click(self::SUBMIT_COMMENT_BUTTON);
        $I->wait(2);
        $I->canSee(self::COMMENT_TEXT);
        $I->canSee('Komentarą būrelio puslapyje galima palikti kartą per 24h');


    }
}
