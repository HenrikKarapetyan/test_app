<?php

namespace App\Controller;

use App\Form\UserType;
use App\Model\UserModel;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user/register')]
#[\OpenApi\Attributes\Tag('User')]
class UserRegisterController extends BaseApiController
{
    #[Route(name: 'register_new_user', methods: ['POST'])]
    public function add(UserService $userService, Request $request): JsonResponse
    {
        $body = $request->getContent();
        $data = json_decode($body, true);
        $user = new UserModel();
        $form = $this->createForm(UserType::class, $user);
        $form->submit($data);
        if ($form->isValid()) {
            $user = $form->getData();
            /**
             * @var $user UserModel
             */
            $userService->setDefaultRole($user);
            $userService->new($user);
            return new JsonResponse(['message' => 'created successfully'], Response::HTTP_ACCEPTED);
        }

        return new JsonResponse($this->getErrorsFromForm($form), Response::HTTP_BAD_REQUEST);
    }
}