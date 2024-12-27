<?php

namespace app\core;

use app\models\Trip;

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
    /**check value size $min ... $max
     * @param $value
     * @param $min
     * @param $max
     * @return bool
     */
    protected static function isSizeValid( $value, $min, $max ):bool
    {
        if(!empty($value)) {
            $len = strlen($value);
            return $len >= $min && $len <= $max;
        }else{
            return false;
        }
    }

    /**
     * check all photos values
     * @param array|null $photo
     * @param bool $isFileNull = true
     * @return string|null
     */
    protected static function validatePhoto(array $photo=null, bool $isFileNull = false): string | null
    {
        if (empty($photo))
        {
            return 'An error occurred, the file was not loaded';
        }
        if ($photo['error'] === UPLOAD_ERR_NO_FILE) {
            if ($photo['name'] === '' && $isFileNull) {
                return null;
            } else {
                return self::UPLOAD_ERROR_MESSAGES[$photo['error']];
            }
        }
        $error = $photo['name'] . '  ' ;
        if ($photo['error'] !== UPLOAD_ERR_OK) {
            return $error . self::UPLOAD_ERROR_MESSAGES[$photo['error']];
        }

        if (!in_array($photo['type'], self::PHOTO_AVAILABLE_TYPES)) {
            return $error . $photo['type'] .'  ' . self::ERROR_MESSAGES[2];
        }
        if ($photo['size'] > self::PHOTO_MAX_SIZE){
            return  $error . $photo['size'] . '  ' . self::ERROR_MESSAGES[3];
        }
        return null;
    }

    /**
     * Check route parameters photo and description
     * @param array $data
     * @return array
     */
    public static function validateRoute(array &$data): array
    {
        $errors = [];
        $res = self::validatePhoto($data['photo'], true);
        if(!empty($res)) {
            $errors[] = $res;
        }
        $data['description'] = $data['description'] ?? '';
        if (empty($data['description'])) {
            $errors[] = 'Description missing';
        }else{
            $data['description'] = trim($data['description']);
            $data['description'] = strip_tags($data['description']);
            $data['description'] = stripcslashes($data['description']);
            $data['description'] = htmlspecialchars($data['description']);
            if ( !self::isSizeValid($data['description'], 15, 400)){
                $errors[] = 'The description does not match the sizes';
            }
        }
        return $errors;
    }

    /**
     * Checking if a trip is in the database
     * @param mixed $trip_id
     * @return bool
     */
    public static function validateTrip(mixed $trip_id): bool
    {
        if (!is_numeric($trip_id)){
            return false;
        }
        $model = new Trip();
        $trip = $model->getById('trips','id', $trip_id);
        if (empty($trip)){
            return false;
        }
        return true;
    }

}