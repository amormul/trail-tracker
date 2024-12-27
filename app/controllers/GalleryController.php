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
     * saves photo in DB
     * @return void
     */
    public function savePhoto(): void
    {
        $userId = $this->getCurrentUserId();
        $tripId = filter_input(INPUT_POST, 'trip_id', FILTER_VALIDATE_INT);
        $comment = filter_input(INPUT_POST, 'comment');
        $file = $_FILES['file'];
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/storage/imageGallery/';
        $relativePath = $this->handleFileUpload($file, $uploadDir);
        $this->gallery->create($userId, $relativePath, $tripId, $comment);
        Route::redirect('/index/show?trip_id=' . $tripId);
    }

    /**
     * shows photo.php
     * @return void
     */
    public function viewPhoto(): void
    {
        $photoId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? $this->session->photo_id;
        $photo = $this->gallery->getPhotoById($photoId);
        $this->session->photo_id = $photoId;
        $photo['likes'] = $this->gallery->countLikes($photoId);
        $tripId = $photo['trip_id'];
        $isOwner = $photo['user_id'] === $this->getCurrentUserId();
        $this->view->render('photo', [
            'title' => 'View Photo',
            'photo' => $photo,
            'tripId' => $tripId,
            'isOwner' => $isOwner
        ]);
    }

    /**
     * "deletes" photo from gallery
     * @return void
     */
    public function deletePhoto(): void
    {
        $photoId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $tripId = filter_input(INPUT_POST, 'trip_id', FILTER_VALIDATE_INT);
        $photo = $this->gallery->getPhotoById($photoId);
        if (!$tripId) {
            $tripId = $photo['trip_id'];
        }
        $this->gallery->delete($photoId);
        Route::redirect('/index/show?trip_id=' . $tripId);
    }

    /**
     * shows edit_photo page
     * @return void
     */
    public function editPhoto(): void
    {
        $photoId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $photo = $this->gallery->getPhotoById($photoId);
        $this->view->render('edit_photo', [
            'title' => 'Edit Photo',
            'photo' => $photo
        ]);
    }

    /**
     * updates photo in DB
     * @return void
     */
    public function update(): void
    {
        $photoId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $comment = filter_input(INPUT_POST, 'comment');
        $photo = $this->gallery->getPhotoById($photoId);
        $file = $_FILES['file'] ?? null;
        $photoPath = $photo['photo'];
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/storage/imageGallery/';
            $photoPath = $this->handleFileUpload($file, $uploadDir, $photoPath);
        }
        $this->gallery->edit($photoId, $photoPath, $comment);
        Route::redirect('/gallery/viewPhoto?id=' . $photoId);
    }

    /**
     * counts, adds and deletes likes from photo
     * @return void
     */
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

    /**
     * create directory if such does not exist
     * @param string $uploadDir
     * @return void
     */
    private function ensureUploadDir(string $uploadDir): void
    {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    }

    /**
     * for uploading or edit photos
     * @param array $file
     * @param string $uploadDir
     * @param string|null $oldFilePath
     * @return string|null
     */
    private function handleFileUpload(array $file, string $uploadDir, string $oldFilePath = null): ?string
    {
        $this->ensureUploadDir($uploadDir);
        $newFileName = 'photo_' . uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $filePath = $uploadDir . $newFileName;
        if ($oldFilePath && file_exists($_SERVER['DOCUMENT_ROOT'] . $oldFilePath)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $oldFilePath);
        }
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            return null;
        }
        return '/storage/imageGallery/' . $newFileName;
    }
}