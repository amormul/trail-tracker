<?php

namespace app\core;

class Helpers
{
    /**
     * copying a file Photo from temp folder to folder images
     * @param string $path
     * @param array|null $photo
     * @return string
     * @throws \Exception
     */
    public static function savePhoto(string $path, array $photo=null): string
    {
        $file = '';
        if(empty($photo)){
            return $file;
        }
        if (!empty($photo['name'])) {
            $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
            $uniqueName = 'img_' . uniqid() . '.' . $extension;
            $fileDir = ltrim($path, DIRECTORY_SEPARATOR);
            $file = $fileDir . DIRECTORY_SEPARATOR . $uniqueName;
            if (!move_uploaded_file($photo['tmp_name'], $file)) {
                $file = '';
                throw new \Exception('Photo was not uploaded: ' . $file);
            }
            $file = DIRECTORY_SEPARATOR . $file;
            var_dump($file);
            $file = str_replace('\\', '/', $file);
        }
        return $file;
    }


    /**
     * deleting a file Photo from  folder images
     * @param string $file
     * @return void
     * @throws \Exception
     */
    public static function deletePhoto(string $file): void
    {
        if(!empty($file)) {
            $fileDir = str_replace('/', DIRECTORY_SEPARATOR, $file);
            $fileDir = ltrim($fileDir, DIRECTORY_SEPARATOR);
            if (!unlink($fileDir)) {
                throw new \Exception('Photo was not deleted: ' . $fileDir);
            }
        }
    }

}