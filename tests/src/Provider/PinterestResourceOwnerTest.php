<?php

namespace Aporat\OAuth2\Client\Test\Provider;

use Aporat\OAuth2\Client\Provider\PinterestResourceOwner;
use PHPUnit\Framework\TestCase;

class PinterestResourceOwnerTest extends TestCase
{
    public function testUrlIsNicknameWithoutDomain(): void
    {
        $user_id = uniqid();
        $username = uniqid();
        $user = new PinterestResourceOwner(['id' => $user_id, 'username' => $username]);

        $this->assertEquals($user_id, $user->getId());
        $this->assertEquals($username, $user->getUsername());
    }
}
