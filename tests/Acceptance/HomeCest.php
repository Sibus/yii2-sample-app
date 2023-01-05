<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;
use yii\helpers\Url;

class HomeCest
{
    public function ensureThatHomePageWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/index'));
        $I->see('Hello World!');
    }
}
