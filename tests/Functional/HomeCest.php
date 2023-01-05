<?php

namespace Tests\Functional;

use Tests\Support\FunctionalTester;
use yii\helpers\Url;

class HomeCest
{
    public function ensureThatHomePageWorks(FunctionalTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/index'));
        $I->see('Hello World!');
    }
}
