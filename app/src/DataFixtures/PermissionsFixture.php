<?php

namespace App\DataFixtures;

use App\Model\PermissionModel;
use App\Services\PermissionService;
use App\Services\RoleService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PermissionsFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly PermissionService $permissionService,
        private readonly RoleService $roleService
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $roles = $this->roleService->getAll();
        $faker = Factory::create();
        foreach ($roles as $role) {
            for ($index = 0; $index < 10; ++$index) {
                $permissionModel = new PermissionModel();
                $permissionModel->setName($faker->name.' '.$index);
                $permissionModel->setDescription($faker->city);
                $permission = $this->permissionService->new($permissionModel);
                $this->roleService->addPermissionForRole($role, $permission);
            }
        }
    }

    public function getDependencies(): array
    {
        return [
            PrimaryDataFixture::class,
        ];
    }
}
