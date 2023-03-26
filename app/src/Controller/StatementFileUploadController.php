<?php

namespace App\Controller;

use App\Entity\Statement;
use App\Form\StatementFileType;
use App\Security\StatementVoter;
use App\Services\StatementService;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[\OpenApi\Attributes\Tag('Statement')]
class StatementFileUploadController extends BaseApiController
{
    public function __construct(private readonly StatementService $statementService)
    {
    }

    #[Route('/api/statement/{id}/file/upload', name: 'app_statement_file_upload', methods: 'POST')]
    public function index(Request $request, int $id): JsonResponse
    {
        $data = $request->files->get('image');
        $form = $this->createForm(StatementFileType::class);
        $form->submit(['image' => $data]);
        $statement = $this->statementService->getById($id);

        $this->denyAccessUnlessGranted(StatementVoter::EDIT, $statement);

        if ($form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            $root_path = $this->getParameter('statement_images_directory');
            $this->statementService->deleteImage($root_path, $statement);
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = md5($originalFilename);
            $newFilename = $safeFilename.'-'.$id.'.'.$imageFile->guessExtension();
            try {
                $imageFile->move($root_path.Statement::FILE_SAVE_PATH, $newFilename);
            } catch (FileException $e) {
                throw new \Exception($e->getMessage());
            }
            $statement->setFileUrl($newFilename);
            $this->statementService->save($statement);

            return new JsonResponse(
                ['message' => 'image uploaded successfully']
            );
        }

        return new JsonResponse($this->getErrorsFromForm($form), Response::HTTP_BAD_REQUEST);
    }
}
