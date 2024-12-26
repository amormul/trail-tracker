<?php

namespace app\controllers;

use app\core\AbstractController;
use app\models\Gallery;

class GalleryController extends AbstractController
{
    /**
     * Display photo page
     * @param int $id
     * @return void
     */
    public function photo(int $id): void
    {
        $galleryModel = new Gallery();
        $photo = $galleryModel->getPhotoById($id);
        if (!$photo) {
            \app\core\Route::notFound();
        }
        $this->view->render('photo', [
            'title' => 'Photo',
            'photo' => $photo,
        ]);
    }

    /**
     * displaying add_photo.php
     * @return void
     */
    public function addPhoto(): void
    {
        $this->view->render('add_photo', [
            'title' => 'Add Photo',
        ]);
    }

    /**
     * displaying edit.php
     * @param int $id
     * @return void
     */
    public function editPhoto(int $id): void
    {
        $gallery = new Gallery();
        $photo = $gallery->getPhotoById($id);

        if (!$photo) {
            \app\core\Route::notFound();
        }
        $this->view->render('edit', [
            'title' => 'Edit photo',
            'photo' => $photo]);
    }

    /**
     * saves changes to photo
     * @param int $id
     * @return void
     */
    public function savePhoto(int $id): void
    {
        $description = $_POST['description'] ?? '';
        $file = $_FILES['file'] ?? null;
        $galleryModel = new Gallery();
        $existingPhoto = $galleryModel->getPhotoPathById($id);
        $photoPath = $existingPhoto;
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/';
            $newFileName = 'photo_' . time() . '_' . basename($file['name']);
            $uploadFile = $uploadDir . $newFileName;

            if ($existingPhoto && file_exists($_SERVER['DOCUMENT_ROOT'] . $existingPhoto)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . $existingPhoto);
            }
            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                $photoPath = '/images/' . $newFileName;
            }
        }
        $success = $galleryModel->updatePhoto($id, $photoPath, $description);
        \app\core\Route::redirect(\app\core\Route::url("gallery", "photo", [$id]));
    }
}