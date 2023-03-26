<?php

namespace App\Services;

use App\Entity\User;
use App\Model\UserModel;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordEncoder,
        private readonly UserRepository $userRepository,
        private readonly RoleService $roleService
    ) {
    }

    public function new(UserModel $userModel): User
    {
        $user = new User();
        $user->setName($userModel->getName());
        $user->setLastname($userModel->getLastname());
        $user->setUsername($userModel->getUsername());
        $user->setEmail($userModel->getEmail());
        foreach ($userModel->getRoles() as $role) {
            $user->addRole($role);
        }
        $hash_line = $this->passwordEncoder->hashPassword($user, $userModel->getPassword());
        $user->setPassword($hash_line);
        $this->userRepository->save($user, true);

        return $user;
    }

    // this method we must use for only console command
    /**
     * @return User []
     */
    public function getUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function getById(string $id): User
    {
        return $this->userRepository->find($id);
    }

    public function getByUsername(string $username): User
    {
        return $this->userRepository->findOneBy(['username' => $username]);
    }

    public function setDefaultRole(UserModel &$user): void
    {
        $role = $this->roleService->getRoleByName('ROLE_CLIENT');
        $roleCollection = new ArrayCollection();
        $roleCollection->add($role);
        $user->setRoles($roleCollection);
    }
}
