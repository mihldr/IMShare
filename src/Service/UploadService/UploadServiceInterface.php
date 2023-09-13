<?php

namespace App\Service\UploadService;

use App\Entity\Key;
use App\Entity\Upload;
use App\Exceptions\FileNotWriteableException;
use App\Exceptions\NotSupportedFileClassificationException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface  UploadServiceInterface {
    /**
     * @throws FileNotWriteableException
     * @throws NotSupportedFileClassificationException
     * @throws FileException
     */
    public function process(UploadedFile $file, Key $key): Upload;

    /**
     * Create a unqiue, non duplicating filename
     *
     * @param UploadedFile $file
     * @param bool $forceRandom
     * @return string
     */
    public function generateFilename(UploadedFile $file, bool $forceRandom): string;
}