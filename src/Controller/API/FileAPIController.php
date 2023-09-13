<?php

namespace App\Controller\API;

use App\Entity\Key;
use App\Exceptions\FileNotWriteableException;
use App\Exceptions\NotSupportedFileClassificationException;
use App\Service\UploadService\UploadServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileAPIController extends AbstractController
{
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
            return new JsonResponse(["error" => "File could not moved to final destination."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
