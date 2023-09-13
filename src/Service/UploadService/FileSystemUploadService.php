<?php

namespace App\Service\UploadService;

use App\Entity\Key;
use App\Entity\Upload;
use App\Exceptions\FileNotWriteableException;
use App\Exceptions\NotSupportedFileClassificationException;
use App\Service\RandomService;
use App\Util\FileClassification;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileSystemUploadService implements UploadServiceInterface {
    public function __construct(private string $fileRepositoryPath, private ManagerRegistry $doctrine) {}

    public function process(UploadedFile $file, Key $key): Upload
    {
        // Check if fileRepository exists
        if(!file_exists($this->fileRepositoryPath))
            mkdir($this->fileRepositoryPath);

        // Check if fileRepository is writeable
        if(!is_writable($this->fileRepositoryPath))
            throw new FileNotWriteableException('File Repository Path is not writeable. Check OS-permissions.');

        // Figure out filetype
        $filetype = FileClassification::MapMimetype($file->getMimeType());
        if(!$filetype) throw new NotSupportedFileClassificationException();

        // Force random file name if image or video
        $filename = in_array($filetype, [FileClassification::IMAGE, FileClassification::VIDEO])
            ? $this->generateFilename($file, true)
            : $this->generateFilename($file);

        // Move file to disk
        $file->move($this->fileRepositoryPath, $filename);

        // Generate and return Upload-Object
        return (new Upload())
            ->setUploadedBy($key)
            ->setName($filename)
            ->setUploadDate(new \DateTime())
            ->setOrigName($file->getClientOriginalName());
    }

    public function generateFilename(UploadedFile $file, bool $forceRandom = false): string
    {
        // If file contains some kind of relative pathing, force it to be random
        if(!str_contains(realpath($this->fileRepositoryPath . "/" . $file->getClientOriginalName()), $this->fileRepositoryPath))
            $forceRandom = true;

        $baseName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        if($forceRandom)
            $baseName = RandomService::generateRandomString(15);

        // Generate unique filename
        while(  $this->doctrine->getRepository(Upload::class)->findOneBy(["name" => $baseName . "." . $extension]) ||
                file_exists($this->fileRepositoryPath . "/". $baseName . "." . $extension)) {
            $baseName .= RandomService::generateRandomString(1);
        }

        return $baseName . "." . $extension;
    }
}