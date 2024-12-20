<?php

namespace app\core;

class Helpers
{
    public static function getPostData(array $fields): array
    {
        $data = [];

        foreach ($fields as $field => $filter) {
            $data[$field] = filter_input(INPUT_POST, $field, $filter);
        }

        return $data;
    }
    /**
     * copying a file Photo from temp folder to folder images
     * @param string $path
     * @param array|null $photo
     * @return string
     * @throws \Exception
     */
    public static function savePhoto(string $path, array $photo = null): string
    {
        $file = '';
        if (empty($photo)) {
            return $file;
        }
        if (!empty($photo['name'])) {
            $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
            $uniqueName = uniqid() . '.' . $extension;
            $fileDir = ltrim($path, DIRECTORY_SEPARATOR);
            $file = $fileDir . DIRECTORY_SEPARATOR . $uniqueName;
            var_dump('save ' . $file);
            if (!move_uploaded_file($photo['tmp_name'], $file)) {
                $file = '';
                throw new \Exception('Photo was not uploaded: ' . $file);
            }
            $file = DIRECTORY_SEPARATOR . $file;
            var_dump('bd ' . $file);
        }
        return $file;
    }


    /**
     * deleting a file Photo from  folder images
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public static function deletePhoto(string $file): void
    {
        if (!empty($file)) {
            $fileDir = ltrim($file, DIRECTORY_SEPARATOR);
            var_dump($file, $fileDir);
            if (!unlink($fileDir)) {
                throw new \Exception('Photo was not deleted: ' . $file);
            }
        }
    }
}
