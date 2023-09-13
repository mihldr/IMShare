<?php

namespace App\Exceptions;

class FileNotWriteableException extends \Exception
{
    public function __construct($message = "File is not writeable", $code = 0, $throwable = null)
    {
        parent::__construct($message, $code, $throwable);
    }
}