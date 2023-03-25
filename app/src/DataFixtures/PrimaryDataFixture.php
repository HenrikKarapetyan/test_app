<?php

namespace App\DataFixtures;

use App\Model\PermissionModel;
use App\Model\RoleModel;
use App\Model\UserModel;
use App\Services\PermissionService;
use App\Services\RoleService;
use App\Services\UserService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;

class PrimaryDataFixture extends Fixture
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        private readonly RoleService $roleService,
        private readonly UserService $userService,
        private readonly PermissionService $permissionService,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $permissionModel = new PermissionModel();
        $permissionModel->setName('can_edit_own_statement');
        $permissionModel->setDescription('User Can edit his own statement');
        $can_edit_own_statement_permission = $this->permissionService->new($permissionModel);

        // in here adding main role
        // its parent role
        // users which contains this role they can do everything
        // whats can do users which have child roles
        $roleAdmin = new RoleModel();
        $roleAdmin->setName('ROLE_ADMIN');
        $roleAdmin->setDescription('Admin role');
        $roleAdmin = $this->roleService->new($roleAdmin);

        // its one of child roles
        $roleClient = new RoleModel();
        $roleClient->setName('ROLE_CLIENT');
        $roleClient->setDescription('Default USer Role');
        $roleClient->setParentRole($roleAdmin);
        $roleClient = $this->roleService->new($roleClient);
        $this->roleService->addPermissionForRole($roleClient, $can_edit_own_statement_permission);

        $admin = new UserModel();
        $admin->setName('Admin');
        $admin->setLastname('Admin');
        $admin->setEmail('admin@gmail.com');
        $admin->setPassword('123456789');
        $roleCollection = new ArrayCollection();
        $roleCollection->add($roleAdmin);
        $admin->setRoles($roleCollection);
        $admin->setUsername('admin');
        $this->userService->new($admin);

        $client = new UserModel();
        $client->setName('client');
        $client->setLastname('client');
        $client->setEmail('client@gmail.com');
        $client->setPassword('123456789');
        $roleCollection = new ArrayCollection();
        $roleCollection->add($roleClient);
        $client->setRoles($roleCollection);
        $client->setUsername('client');
        $this->userService->new($client);
    }
}
