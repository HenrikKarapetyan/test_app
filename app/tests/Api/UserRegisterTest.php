<?php

namespace App\Tests\Api;

use App\Model\UserModel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRegisterTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient([], [
            'HTTP_HOST' => 'my-app.local',
        ]);
    }

    /**
     * @dataProvider getUsersDataProvider
     */
    public function testUsersRegistration(UserModel $userModel): void
    {
        $this->client->request('POST', '/api/user/register',
            [],
            [],
            [],
            json_encode($userModel->asArray())
        );
        $this->assertResponseIsSuccessful();
    }

    public static function getUsersDataProvider(): array
    {
        $simpleUser = new UserModel();
        $simpleUser->setName('simple_user1');
        $simpleUser->setLastname('lastname');
        $simpleUser->setPassword('123456789');
        $simpleUser->setUsername('simple_client');
        $simpleUser->setEmail('simple_user@gmail.com');

        $simpleUser2 = new UserModel();
        $simpleUser2->setName('simple_user2');
        $simpleUser2->setLastname('lastname');
        $simpleUser2->setPassword('123456789');
        $simpleUser2->setUsername('simple_client2');
        $simpleUser2->setEmail('simple_user2@gmail.com');

        return [
            [
                $simpleUser2,
            ],
            [
                $simpleUser,
            ],
        ];
    }
}
