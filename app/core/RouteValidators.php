<?php

namespace app\core;

class RouteValidators
{
    const  PHOTO_MAX_SIZE = 5242880;//5 * 1024 * 1024; //5Mb
    const PHOTO_AVAILABLE_TYPES = [
        'image/jpeg',
        'image/png',
        'image/bmp',
    ];
    const UPLOAD_ERROR_MESSAGES = [
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    ];

    const  ERROR_MESSAGES = [
        0 => 'Please choose at least one file for upload',
        1 => 'No more than 5 files allowed to upload',
        2 => ' has incorrect format. Allowed formats: jpg, jpeg, png, bmp',
        3 => ' more than max file size - 2 Mb.'
    ];

    /**
     * Checking if the file has been downloaded
     * @param array $file
     * @return string|null
     */
    protected static function isNoFileError(array $file): string | null
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return $file['name'] . '  ' . self::UPLOAD_ERROR_MESSAGES[$file['error']];
        }
        return null;
    }

    /**
     * Check if the file is of an allowed type mime
     * @param array $file
     * @return string|null
     */
    protected static function checkTypePhoto(array $file): string | null
    {
        if (!in_array($file['type'], self::PHOTO_AVAILABLE_TYPES)) {
            return $file['name'] . ' ' . $file['type'] .'  ' . self::ERROR_MESSAGES[2];
        }
        return null;
    }

    /**
     * Check size of the file
     * @param array $file
     * @param int $maxFileSize
     * @return string|null
     */
    protected static function checkSizePhoto(array $file, int $maxFileSize): string | null
    {
        if ($file['size'] > $maxFileSize){
            return $file['name'] . ' ' . $file['size'] . '  ' . self::ERROR_MESSAGES[3];
        }
        return null;
    }

    /**
     * check all photos values
     * @param array|null $photo
     * @return string|null
     */
    protected static function validatePhoto(array $photo=null): string | null
    {
        if(empty($photo)){
            return null;
        }
        $res = self::isNoFileError($photo);
        if(!empty($res)){
            return  $res;
        }
        $res = self::checkTypePhoto($photo);
        if(!empty($res)){
            return  $res;
        }
        $res = self::checkSizePhoto($photo, self::PHOTO_MAX_SIZE);
        if(!empty($res)) {
            return  $res;
        }
        return null;
    }
    public static function validateRoute(array $data): array
    {
        $errors = [];
        $res = self::validatePhoto($data['photo']);
        if(!empty($res)) {
            $errors[] = $res;
        }

        return $errors;
    }


}