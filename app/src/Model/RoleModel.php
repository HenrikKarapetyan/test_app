<?php

namespace App\Model;

use App\Entity\Roles;

class RoleModel
{
    private ?string $name = null;
    private ?string $description = null;
    private ?Roles $parent_role = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getParentRole(): ?Roles
    {
        return $this->parent_role;
    }

    public function setParentRole(?Roles $parent_role): void
    {
        $this->parent_role = $parent_role;
    }
}
