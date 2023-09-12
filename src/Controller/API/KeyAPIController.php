<?php

namespace App\Controller\API;

use App\Entity\Key;
use App\Service\RandomService;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KeyAPIController extends AbstractController
{
    #[Route('/user_api/keys/{limit}', name: 'api_keys_get', methods: ['GET'])]
    public function get_keys(ManagerRegistry $doctrine, Security $security, int $limit = 10): Response
    {
        $keys = $doctrine->getRepository(Key::class)->findBy(["generated_by" => $security->getUser()], ["generated_at" => "DESC"], $limit);

        return new JsonResponse($keys);
    }

    #[Route('/user_api/keys', name: 'api_key_put', methods: ['PUT'])]
    public function put_key(ManagerRegistry $doctrine, Security $security) {
        // Make sure secret is unique when persisting
        $uniqueSecret = RandomService::generateRandomString(10);
        while($doctrine->getRepository(Key::class)->findOneBy(["secret" => $uniqueSecret]))
            $uniqueSecret = RandomService::generateRandomString(10);

        // Generate new key object
        $key = (new Key())
            ->setAllowDocuments(true)
            ->setAllowExecutables(true)
            ->setAllowImages(true)
            ->setAllowVideos(true)
            ->setIsActive(true)
            ->setGeneratedAt(new DateTime())
            ->setGeneratedBy($security->getUser())
            ->setSecret(RandomService::generateRandomString(25));

        // Persist and flush key
        $doctrine->getManager()->persist($key);
        $doctrine->getManager()->flush();

        return new JsonResponse($key->toArray());
    }

    #[Route('/user_api/key/{key}', name: 'api_key_patch', methods: ['PATCH'])]
    public function patch_key(Request $request, ManagerRegistry $doctrine, Security $security, string $key = null) {
        // Parse JSON content
        $jsonContent = json_decode($request->getContent(), true);
        if(!$jsonContent) return new JsonResponse(["error" => "Body content is not JSON!"], RESPONSE::HTTP_UNPROCESSABLE_ENTITY);

        // Verify if key is even set.
        if(!$key) return new JsonResponse(["error" => "No key has been specified"], RESPONSE::HTTP_UNPROCESSABLE_ENTITY);

        // Get key object first
        $key = $doctrine->getRepository(Key::class)->findOneBy(["secret" => $key, "generated_by" => $security->getUser()]);
        if(!$key) return new JsonResponse(["error" => "Could not find key"], RESPONSE::HTTP_UNPROCESSABLE_ENTITY);

        if(isset($jsonContent["is_active"]) && is_bool($jsonContent["is_active"]))
            $key->setIsActive($jsonContent["is_active"]);
        if(isset($jsonContent["allow_images"]) && is_bool($jsonContent["allow_images"]))
            $key->setAllowImages($jsonContent["allow_images"]);
        if(isset($jsonContent["allow_videos"]) && is_bool($jsonContent["allow_videos"]))
            $key->setAllowVideos($jsonContent["allow_videos"]);
        if(isset($jsonContent["allow_executables"]) && is_bool($jsonContent["allow_executables"]))
            $key->setAllowExecutables($jsonContent["allow_executables"]);
        if(isset($jsonContent["allow_documents"]) && is_bool($jsonContent["allow_documents"]))
            $key->setAllowDocuments($jsonContent["allow_documents"]);

        $doctrine->getManager()->persist($key);
        $doctrine->getManager()->flush();

        return new JsonResponse($key->toArray());
    }
}
