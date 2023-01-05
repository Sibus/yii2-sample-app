<?php

namespace Tests\Unit\models;

use app\models\User;
use Tests\Support\UnitTester;

class UserTest extends \Codeception\Test\Unit
{
    public function testFindUserById()
    {
        $user = User::findIdentity(100);
        $this->assertNotEmpty($user);
        $this->assertEquals('admin', $user->username);

        $notExistingUser = User::findIdentity(999);
        $this->assertEmpty($notExistingUser);
    }

    public function testFindUserByAccessToken()
    {
        $user = User::findIdentityByAccessToken('100-token');
        $this->assertNotEmpty($user);
        $this->assertEquals('admin', $user->username);

        $notExistingUser = User::findIdentityByAccessToken('non-existing');
        $this->assertEmpty($notExistingUser);
    }
}
