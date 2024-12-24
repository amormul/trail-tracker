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
        if (!$tripId) {
            $this->view->render('error', ['message' => 'Trip ID is required.']);
            return;
        }
        $this->view->render('add_photo', [
            'title' => 'Add New Photo',
            'tripId' => $tripId
        ]);
    }

    public function savePhoto(): void
    {
        $userId = $this->getCurrentUserId();
        if (!$userId) {
            $this->view->render('error', ['message' => 'User not authenticated.']);
            return;
        }
        $tripId = filter_input(INPUT_POST, 'trip_id', FILTER_VALIDATE_INT);
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
        if (!$tripId || !isset($_FILES['file'])) {
            $this->view->render('error', ['message' => 'Invalid input data.']);
            return;
        }
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/storage/imageGallery/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $file = $_FILES['file'];
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = uniqid('photo_', true) . '.' . $fileExtension;
        $filePath = $uploadDir . $newFileName;
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            $this->view->render('error', ['message' => 'File upload failed.']);
            return;
        }
        $relativePath = '/storage/imageGallery/' . $newFileName;
        $this->gallery->create($userId, $relativePath, $tripId, $comment);
        Route::redirect('/index/show?trip_id=' . $tripId);
    }

    public function viewPhoto(): void
    {
        $photoId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? $this->session->photo_id;
        if (!$photoId) {
            $this->view->render('error', ['message' => 'Invalid photo ID.']);
            return;
        }
        $photo = $this->gallery->getPhotoById($photoId);
        if (!$photo) {
            $this->view->render('error', ['message' => 'Photo not found.']);
            return;
        }
        $this->session->photo_id = $photoId;
        $photo['likes'] = $this->gallery->countLikes($photoId);
        $this->view->render('photo', [
            'title' => 'View Photo',
            'photo' => $photo
        ]);
    }

    public function like(): void
    {
        $photoId = filter_input(INPUT_POST, 'photo_id', FILTER_VALIDATE_INT);
        if (!$photoId) {
            $this->view->render('error', ['message' => 'Invalid photo ID.']);
            return;
        }
        $userId = $this->getCurrentUserId();
        if ($this->gallery->checkLike($photoId, $userId)) {
            $this->gallery->addLike($photoId, $userId);
        } else {
            $this->gallery->deleteLike($photoId, $userId);
        }
        Route::redirect($_SERVER['HTTP_REFERER'] ?? '/index/index');
    }
}