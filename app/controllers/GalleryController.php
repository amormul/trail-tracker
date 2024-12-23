<?php

namespace app\controllers;

use app\core\AbstractController;
use app\core\Route;
use app\models\Gallery;

class GalleryController extends AbstractController
{
    private Gallery $gallery;

    public function __construct()
    {
        parent::__construct();
        $this->gallery = new Gallery();
    }
    /**
     * opens add_photo page
     * @return void
     */
    public function addPhoto(): void
    {
        $tripId = filter_input(INPUT_GET, 'trip_id', FILTER_VALIDATE_INT);
        $this->view->render('add_photo', [
            'title' => 'Add New Photo',
            'tripId' => $tripId
        ]);
    }
    /**
     * @return void
     */
    public function viewPhoto(): void
    {
        $photoId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $photo = $this->gallery->getPhotoById($photoId);
        $this->view->render('photo', [
            'title' => 'Photo Details',
            'photo' => $photo,
        ]);
    }

    public function backToPhoto(): void
    {
        $photoId = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $photo = $this->gallery->getPhotoById($photoId);
        $this->view->render('photo', [
            'photo' => $photo,
            'title' => 'View Photo'
        ]);
    }


    public function editPhoto(): void
    {
        $photoId = $_POST['id'] ?? null;
        $photo = $this->gallery->getPhotoById($photoId);
        $this->view->render('edit_photo', [
            'title' => 'Edit Photo',
            'photo' => $photo
        ]);
    }

    public function update(): void
    {
        $photoId = isset($_POST['id']) ? (int)$_POST['id'] : null;
        $existingPhoto = $this->gallery->getPhotoById($photoId);
        $file = $_FILES['file'] ?? null;
        $photoPath = $existingPhoto['photo'];
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/storage/imageGallery/';
            $newFileName = 'photo_' . uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $uploadFile = $uploadDir . $newFileName;
            if ($photoPath && file_exists($_SERVER['DOCUMENT_ROOT'] . $photoPath)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . $photoPath);
            }
            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                $photoPath = '/storage/imageGallery/' . $newFileName;
            }
        }
        $comment = $_POST['comment'] ?? $existingPhoto['comment'];
        $this->gallery->edit($photoId, $photoPath, $comment);
        unset($gallery);
        $route = new Route();
        $route->redirect('/gallery/viewPhoto?id=' . $photoId);
    }
}