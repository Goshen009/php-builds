<?php

declare(strict_types=1);

namespace App;

class Helpers {
    public static function getFileUploadErrorMessage(int $errorCode): string {
        return match ($errorCode) {
            UPLOAD_ERR_OK         => "No error, the file uploaded successfully.",
            UPLOAD_ERR_INI_SIZE   => "The uploaded file exceeds the upload_max_filesize directive in php.ini.",
            UPLOAD_ERR_FORM_SIZE  => "The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form.",
            UPLOAD_ERR_PARTIAL    => "The file was only partially uploaded.",
            UPLOAD_ERR_NO_FILE    => "No file was uploaded.",
            UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder.",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
            UPLOAD_ERR_EXTENSION  => "A PHP extension stopped the file upload.",
            default               => "Unknown upload error (Code: $errorCode)."
        };
    }
}