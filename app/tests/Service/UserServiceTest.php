<?php

namespace App\Tests\Service;

use App\Model\UserModel;
use App\Repository\UserRepository;
use App\Services\RoleService;
use App\Services\UserService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserServiceTest extends KernelTestCase
{
    private ?UserRepository $userRepository;

    private ?UserService $userService;
    private ?UserPasswordHasherInterface $passwordEncoder;
    private ?RoleService $roleService;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->roleService = $this->getContainer()->get(RoleService::class);

        $this->passwordEncoder = $this->getContainer()->get(UserPasswordHasherInterface::class);

        $this->userService = new UserService(
            $this->passwordEncoder,
            $this->userRepository,
            $this->roleService
        );
    }

    /**
     * @dataProvider getOneStatementDataProvider
     */
    public function testCreateUser(int $id, UserModel $user, UserModel $expected): void
    {
        $result = $this->userService->new($user);
        $this->assertEquals($expected->getName(), $result->getName());
        $this->assertEquals($expected->getLastname(), $result->getLastname());
        $this->assertEquals($expected->getUsername(), $result->getUsername());
        $this->assertEquals($expected->getEmail(), $result->getEmail());
    }

    public function getOneStatementDataProvider(): array
    {
        $this->roleService = $this->getContainer()->get(RoleService::class);
        $id = 1;
        $userModel = new UserModel();
        $userModel->setName('test name');
        $userModel->setLastname('test user');
        $userModel->setUsername('testuser');
        $userModel->setPassword('123456789');
        $userModel->setEmail('testuser@gmail.com');
        $roleByName = $this->roleService->getRoleByName('ROLE_CLIENT');
        $arrayCollection = new ArrayCollection();
        $arrayCollection->add($roleByName);
        $userModel->setRoles($arrayCollection);
        $expected = $userModel;

        return [
            [$id, $userModel, $expected],
        ];
    }

    protected function tearDown(): void
    {
        $this->userRepository = null;
        $this->passwordEncoder = null;
        $this->roleService = null;
        $this->userService = null;
    }
}
