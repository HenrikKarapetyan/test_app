<?php

namespace App\Controller;

use App\Form\StatementType;
use App\Message\NewStatementNotification;
use App\Model\StatementModel;
use App\Security\StatementVoter;
use App\Services\StatementService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/statement')]
#[\OpenApi\Attributes\Tag('Statement')]
class StatementController extends BaseApiController
{
    #[Route(name: 'add_new_statement', methods: ['POST'])]
    public function add(MessageBusInterface $bus, Request $request): JsonResponse
    {
        $body = $request->getContent();
        $data = json_decode($body, true);
        $statement = new StatementModel();
        $statement->setAuthorId($this->getUser()->getUserIdentifier());
        $form = $this->createForm(StatementType::class, $statement);
        $form->submit($data);
        if ($form->isValid()) {
            /**
             * @param $statement StatementModel
             */
            $statement = $form->getData();
            $bus->dispatch(new NewStatementNotification($statement));

            return new JsonResponse(['message' => 'created successfully'], Response::HTTP_ACCEPTED);
        }

        return new JsonResponse($this->getErrorsFromForm($form), Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}', name: 'edit_statement', methods: ['PUT'])]
    public function edit(MessageBusInterface $bus, Request $request, StatementService $statementService, int $id): JsonResponse
    {
        $body = $request->getContent();
        $data = json_decode($body, true);
        $statement = $statementService->getById($id);
        $statementData = $statementService->initializeAndGetStatementModelFromEntity($statement);
        $this->denyAccessUnlessGranted(StatementVoter::EDIT, $statement);
        $form = $this->createForm(StatementType::class, $statementData);
        $form->submit($data, false);
        if ($form->isValid()) {
            /**
             * @param $statementData StatementModel
             */
            $statementData = $form->getData();
            $statementService->update($statement, $statementData);

            return new JsonResponse(['message' => 'Edited successfully'], Response::HTTP_ACCEPTED);
        }

        return new JsonResponse($this->getErrorsFromForm($form), Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}', name: 'delete_statement', methods: ['DELETE'])]
    public function delete(MessageBusInterface $bus, Request $request, StatementService $statementService, int $id): JsonResponse
    {
        $statement = $statementService->getById($id);
        $root_path = $this->getParameter('statement_images_directory');
        $this->denyAccessUnlessGranted(StatementVoter::DELETE, $statement);
        $statementService->delete($statement, $root_path);

        return new JsonResponse(['message' => 'Deleted successfully'], Response::HTTP_ACCEPTED);
    }
}
