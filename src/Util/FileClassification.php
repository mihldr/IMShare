<?php

namespace App\Util;

enum FileClassification {
    case IMAGE;
    case VIDEO;
    case EXECUTABLE;
    case DOCUMENT;

    public static function MapMimetype($mimetype): FileClassification|null {
        switch($mimetype) {
            // Images
            case 'image/bmp':
            case 'image/gif':
            case 'image/jpeg':
            case 'image/vnd.microsoft.icon':
            case 'image/png':
            case 'image/svg+xml':
            case 'image/tiff':
            case 'image/webp':
                return FileClassification::IMAGE;

            // Videos
            case 'video/x-msvideo':
            case 'video/mpeg':
            case 'video/ogg':
            case 'video/mp2t':
            case 'video/webm':
            case 'video/3gpp':
            case 'video/3gpp2':
            case 'video/mp4':
            case 'video/x-flv':
            case 'video/quicktime':
            case 'video/x-ms-wmv':
                return FileClassification::VIDEO;

            case 'application/octet-stream':
            case 'application/x-macbinary':
                return FileClassification::EXECUTABLE;

            case 'application/x-abiword':
            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
            case 'text/html':
            case 'text/javascript':
            case 'application/json':
            case 'application/ld+json':
            case 'application/vnd.oasis.opendocument.presentation':
            case 'application/vnd.oasis.opendocument.spreadshee':
            case 'application/vnd.oasis.opendocument.text':
            case 'application/pdf':
            case 'application/php':
            case 'application/vnd.ms-powerpoint':
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
            case 'application/rtf':
            case 'application/xhtml+xml':
            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            case 'application/xml':
            case 'text/xml':
            case 'application/x-latex':
                return FileClassification::DOCUMENT;

            default:
                return null;
        }
    }
}