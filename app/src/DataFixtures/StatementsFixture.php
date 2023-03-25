<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Model\StatementModel;
use App\Services\StatementService;
use App\Services\UserService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class StatementsFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserService $userService,
        private readonly StatementService $statementService
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $users = $this->userService->getUsers();
        /**
         * @param $user User
         */
        foreach ($users as $user) {
            for ($index = 0; $index < 10; ++$index) {
                $statementModel = new StatementModel();
                $statementModel->setName($faker->name);
                $statementModel->setNumber($index);
                $statementModel->setAuthor($user);
                $this->statementService->new($statementModel);
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
