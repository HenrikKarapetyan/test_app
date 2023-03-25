<?php

namespace App\Services;

use App\Entity\Permissions;
use App\Model\PermissionModel;
use App\Repository\PermissionsRepository;

class PermissionService
{
    public function __construct(private readonly PermissionsRepository $permissionsRepository)
    {
    }

    public function new(PermissionModel $permissionModel): Permissions
    {
        $permission = new Permissions();
        $permission->setName($permissionModel->getName());
        $permission->setDescription($permissionModel->getDescription());
        $this->permissionsRepository->save($permission, true);

        return $permission;
    }
}
