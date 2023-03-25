<?php

namespace App\Services;

use App\Entity\Permissions;
use App\Entity\Roles;
use App\Model\RoleModel;
use App\Repository\RolesRepository;

class RoleService
{
    public function __construct(private RolesRepository $rolesRepository)
    {
    }

    public function new(RoleModel $roleModel): Roles
    {
        $role = new Roles();
        $role->setName($roleModel->getName());
        $role->setDescription($roleModel->getDescription());
        $role->setParentRole($roleModel->getParentRole());
        $this->rolesRepository->save($role, true);

        return $role;
    }

    // this method using for only console command
    public function getAll(): array
    {
        return $this->rolesRepository->findAll();
    }

    public function addPermissionForRole(Roles $role, Permissions $permission): Roles
    {
        $role->addPermission($permission);
        $this->rolesRepository->save($role, true);

        return $role;
    }

    public function getRoleByName(string $role_name): ?Roles
    {
        return $this->rolesRepository->findOneBy(['name'=>$role_name]);
    }
}
