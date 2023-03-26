<?php

namespace App\Tests\Service;

use App\Entity\Statement;
use App\Repository\StatementRepository;
use App\Services\StatementService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StatementServiceTest extends KernelTestCase
{
    private ?StatementRepository $statementRepository;
    private ?UserService $userService;
    private ?StatementService $statementService;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->statementRepository = $this->getMockBuilder(StatementRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->userService = $this->getMockBuilder(UserService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->statementService = new StatementService(
            $this->statementRepository,
            $this->userService
        );
    }

    public function testCreateStatement(): void
    {
        $user = $this->userService->getByUsername('client');

        $statement = new Statement();
        $statement->setName('test name');
        $statement->setNumber(50);
        $statement->setAuthor($user);

        $result = $this->statementService->save($statement);
        $this->assertEquals('test name', $result->getName());
        $this->assertEquals(50, $result->getNumber());
        $this->assertEquals($result->getAuthor(), $user);
    }

    /**
     * @dataProvider getStatementDataProvider
     */
    public function testGet($id, $statement, $expected)
    {
        $this->statementRepository
            ->expects($this->any())
            ->method('find')
            ->with($id)
            ->willReturn($statement);

        $result = $this->statementService->getById($id);

        $this->assertEquals($expected, $result);
    }

    public function getStatementDataProvider(): array
    {
        $id1 = 1;
        $statement = new Statement();
        $statement->setName('test name');
        $statement->setNumber(50);
        $expected = $statement;

        return [
            [$id1, $statement, $expected],
        ];
    }

    /**
     * @dataProvider getUpdateStatementDataProvider
     */
    public function testUpdate($id, $statement)
    {
        $this->statementRepository
            ->expects($this->any())
            ->method('find')
            ->with($id)
            ->willReturn($statement);
        $statementNew = new Statement();
        $statementNew->setName('asdadad');
        $statementNew->setNumber(7854);
        $statement = $this->statementService->save($statementNew);
        $this->assertEquals('asdadad', $statement->getName());
        $this->assertEquals(7854, $statement->getNumber());
    }

    public function getUpdateStatementDataProvider(): array
    {
        $statement = new Statement();
        $statement->setNumber(1);
        $statement->setName('Name 121');

        return [
            [1, $statement],
        ];
    }

    /**
     * @dataProvider getUpdateStatementDataProvider
     */
    public function testDelete($id, $statement): void
    {
        $this->statementRepository
            ->expects($this->any())
            ->method('find')
            ->with($id)
            ->willReturn($statement);

        $this->statementService->delete($statement, '');
    }

    protected function tearDown(): void
    {
        $this->statementRepository = null;
        $this->userService = null;
        $this->statementService = null;
    }
}
