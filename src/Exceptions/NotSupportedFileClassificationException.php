<?php

namespace App\Exceptions;

class NotSupportedFileClassificationException extends \Exception
{
    public function __construct($message = "Filetype is not supported", $code = 0, $throwable = null)
    {
        parent::__construct($message, $code, $throwable);
    }
}