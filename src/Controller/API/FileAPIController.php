<?php

namespace App\Controller\API;

use App\Entity\Key;
use App\Entity\Upload;
use App\Exceptions\FileNotWriteableException;
use App\Exceptions\NotSupportedFileClassificationException;
use App\Service\UploadService\UploadServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileAPIController extends AbstractController
{
    #[Route('/user_api/uploads/{page}', name: 'api_uploads_get', methods: ["GET"])]
    public function api_uploads_get(Request $request, ManagerRegistry $doctrine, Security $security, int $page = 0): Response {
        $uploads = $doctrine->getRepository(Upload::class)->getUploadsByUserPaginated($security->getUser(), $page);

        $tmpArray = [];
        foreach($uploads as $upload) {
            $tmpArray[] = ["name" => $upload->getName(), "orig_name" => $upload->getOrigName()];

            // TODO - need filetype, can't expect it to be all images/videos
        }

        return new JsonResponse($tmpArray);
    }

    #[Route('/api/file/upload', name: 'api_file_upload', methods: ["POST"])]
    public function index(Request $request, ManagerRegistry $doctrine, UploadServiceInterface $uploadService): Response
    {
        // Check if a file has been transmitted
        $file = $request->files->get('file');
        if(!$file) return new JsonResponse(["error" => "No file has been transmitted"], Response::HTTP_UNPROCESSABLE_ENTITY);

        // Get secret-input
        $secretInput = $request->headers->get('secret');
        if(!$secretInput) return new JsonResponse(["error" => "Secret-Key is missing"], Response::HTTP_UNAUTHORIZED);

        // Get key object from database based on secret-input
        $key = $doctrine->getRepository(Key::class)->findOneBy(["secret" => $secretInput, "is_active" => true]);
        if(!$key) return new JsonResponse(["error" => "Invalid Secret-Key"], Response::HTTP_UNAUTHORIZED);

        // Try processing file
        $uploaded = null;
        try {
            $uploaded = $uploadService->process($file, $key);

            $doctrine->getManager()->persist($uploaded);
            $doctrine->getManager()->flush();

            return new JsonResponse(["success" => ["filename" => $uploaded->getName()]]);
        } catch (FileNotWriteableException $e) {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch(NotSupportedFileClassificationException $e) {
            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch(FileException $e) {
            return new JsonResponse(["error" => "Could not move file to final destination."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
