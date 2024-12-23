<?php

namespace app\controllers;

use app\core\AbstractController;
use app\core\Route;
use app\core\Session;
use app\models\Gallery;

class GalleryController extends AbstractController
{
    private Gallery $gallery;
    protected Session $session;

    public function __construct()
    {
        parent::__construct();
        $this->gallery = new Gallery();
        $this->session = new Session();
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
     * shows photo info
     * @return void
     */
    public function viewPhoto(): void
    {
        $photoId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? $this->session->photo_id;
        $photo = $this->gallery->getPhotoById($photoId);
        $photo['likes'] = $this->gallery->countLikes($photoId);
        $this->view->render('photo', [
            'title' => 'Photo Details',
            'photo' => $photo
        ]);
    }

    /**
     * shows edit_photo page
     * @return void
     */
    public function editPhoto(): void
    {
        $photoId = $_POST['id'] ?? null;
        $photo = $this->gallery->getPhotoById($photoId);
        $this->view->render('edit_photo', [
            'title' => 'Edit Photo',
            'photo' => $photo
        ]);
    }

    /**
     * updates photo info in DB
     * @return void
     */
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
        \app\core\Route::redirect('/index/show');
    }

    public function like(): void
    {
        $photoId = filter_input(INPUT_POST, 'photo_id', FILTER_VALIDATE_INT);

        if ($photoId) {
            $this->toggleLike($photoId);
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? '/index/index';
        if (strpos($referer, '/gallery/viewPhoto') !== false && $photoId) {
            $this->session->photo_id = $photoId;
        }
        Route::redirect($referer);
    }

    private function toggleLike(int $photoId): void
    {
        $this->gallery->checkLike($photoId, 1)
            ? $this->gallery->addLike($photoId, 1)
            : $this->gallery->deleteLike($photoId, 1);
    }
}