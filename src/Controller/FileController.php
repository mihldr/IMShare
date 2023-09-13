<?php

namespace App\Controller;

use App\Service\UploadService\UploadServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController
{
    #[Route('/{basename}.{extension}', name: 'file_get', methods: ["GET"])]
    public function get_file(string $basename, string $extension, Request $request, ManagerRegistry $doctrine, UploadServiceInterface $uploadService): Response {
        try {
            return $uploadService->getFile($basename . "." . $extension);
        } catch (InvalidArgumentException $e) {
            return new Response(null, 404);
        }
    }
}
