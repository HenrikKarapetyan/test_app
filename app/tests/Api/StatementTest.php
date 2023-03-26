<?php

namespace App\Tests\Api;

use App\Model\StatementModel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StatementTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient([], [
            'HTTP_HOST' => 'my-app.local',
        ]);
    }

    /**
     * @dataProvider getStatementsDataProvider
     */
    public function testCreateStatement(StatementModel $statementModel): void
    {
        $token = $this->login();
        $this->client->request('POST', '/api/statement', [], [], [
            'HTTP_AUTHORIZATION' => "Bearer {$token}",
            'CONTENT_TYPE' => 'application/json',
        ],
            json_encode($statementModel->asArray()));
        $this->assertResponseIsSuccessful();
    }

    public function login(): string
    {
        $userModel = UserRegisterTest::getUsersDataProvider()[0][0];
        $this->client->request('POST', '/api/login_check',
            [], [], [
                'CONTENT_TYPE' => 'application/json',
            ], json_encode([
                'username' => $userModel->getUsername(),
                'password' => $userModel->getPassword(),
            ]));
        $this->assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token', $responseData);

        return $responseData['token'];
    }

    public function getStatementsDataProvider(): array
    {
        $st1 = new StatementModel();
        $st1->setName('test name55');
        $st1->setNumber(50);

        $st2 = new StatementModel();
        $st2->setName('test name');
        $st2->setNumber(50);

        return [
            [$st1], [$st2],
        ];
    }
}
