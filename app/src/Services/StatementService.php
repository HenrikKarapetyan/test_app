<?php

namespace App\Services;

use App\Entity\Statement;
use App\Model\StatementModel;
use App\Repository\StatementRepository;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class StatementService
{
    public function __construct(
        private readonly StatementRepository $statementRepository,
        private readonly UserService $userService
    ) {
    }

    public function new(StatementModel $statementModel): Statement
    {
        $statement = new Statement();
        $statement->setName($statementModel->getName());
        $statement->setNumber($statementModel->getNumber());
        if (null !== $statementModel->getAuthor()) {
            $statement->setAuthor($statementModel->getAuthor());
        } else {
            $author = $this->userService->getById($statementModel->getAuthorId());
            $statement->setAuthor($author);
        }
        $this->statementRepository->save($statement, true);

        return $statement;
    }

    public function getById(int $id): Statement
    {
        $statement = $this->statementRepository->find($id);
        if (!$statement) {
            throw new BadRequestException("The statement by this id: $id not found");
        }

        return $statement;
    }

    public function save(Statement $statement): Statement
    {
        $this->statementRepository->save($statement, true);

        return $statement;
    }

    public function update(Statement $statement, StatementModel $statementModel): Statement
    {
        $statement->setName($statementModel->getName());
        $statement->setNumber($statementModel->getNumber());
        $this->statementRepository->save($statement, true);

        return $statement;
    }

    public function initializeAndGetStatementModelFromEntity(Statement $statement): StatementModel
    {
        $statementModel = new StatementModel();
        $statementModel->setName($statement->getName());
        $statementModel->setNumber($statement->getNumber());

        return $statementModel;
    }

    public function deleteImage(string $path, Statement $statement): void
    {
        if (!empty($statement->getFileUrl())) {
            try {
                unlink($path.$statement->getFileUrl());
            } catch (FileNotFoundException $e) {
            }
        }
    }

    public function delete(Statement $statement, string $root_path): void
    {
        $this->deleteImage($root_path, $statement);
        $this->statementRepository->remove($statement, true);
    }
}
