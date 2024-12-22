<?php

namespace app\controllers;

use app\core\AbstractController;
use app\core\Helpers;
use app\core\Route;
use app\core\Session;
use app\models\Inventory;
use app\models\Trip;
use RuntimeException;
use app\core\TripValidator;
use app\models\Gallery;

class IndexController extends AbstractController
{
    protected Trip $model;
    protected Session $session;
    protected TripValidator $validator;
    private string $fileDir = 'storage' . DIRECTORY_SEPARATOR . 'imageTrip' . DIRECTORY_SEPARATOR;
    private array $fields = [
        'name' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'description' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'difficulty_id' => FILTER_VALIDATE_INT,
        'status_id' => FILTER_VALIDATE_INT,
        'start_date' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'end_date' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->session = new Session();
        $this->model = new Trip();
        $this->validator = new TripValidator();
    }

    public function index(): void
    {
        $trips = $this->enrichTrips($this->model->getAll());
        $this->view->render('index', [
            'title' => 'All trips',
            'trips' => $trips
        ]);
    }

    public function show(): void
    {
        $tripId = filter_input(INPUT_POST, 'trip_id', FILTER_VALIDATE_INT) ?: $this->session->trip_id;
        $trip = $this->getEnrichedTrip($tripId);
        $route = $this->model->getById('routes', 'trip_id', $trip['id']);
        $inventories = $this->enrichInventoryWithParams($this->model->getInventoryByTrip($tripId));
        $photos = $this->getPhotosForTrip($tripId);

        $this->view->render('trip', [
            'title' => 'Trip page',
            'trip' => $trip,
            'route' => $route,
            'inventories' => $inventories,
            'photos' => $photos
        ]);
    }

    public function create(): void
    {
        $errors = $this->session->errors ?? [];
        $old = $this->session->old ?? [];

        $this->session->remote('old');
        $this->session->remote('errors');

        $this->view->render('add_trip', [
            'title' => 'Create trip',
            'difficulties' => $this->model->getAllDifficulties(),
            'statuses' => $this->model->getAllStatuses(),
            'inventories' => (new Inventory())->getAllInventory(),
            'old' => $old,
            'errors' => $errors
        ]);
    }

    public function store(): void
    {
        $data = Helpers::getPostData($this->fields);
        $inventory = filter_input(INPUT_POST, 'inventory', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $errors = $this->validator->validate($data);

        if (empty($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $errors['photo'] = 'Photo is required and must be a valid file.';
        }

        if (!empty($errors)) {
            $this->session->old = $data;
            $this->session->errors = $errors;
            Route::redirect('/index/create');
        }

        $data['photo'] = Helpers::savePhoto($this->fileDir, $_FILES['photo']);
        $data['user_id'] = 1;
        $tripId = $this->model->create($data);

        if ($tripId) {
            $this->storeTripInventory($tripId, $inventory);
        } else {
            throw new RuntimeException('Failed to create trip.');
        }

        $this->session->trip_id = $tripId;
        Route::redirect('/index/show');
    }

    public function edit(): void
    {
        $tripId = $this->getTripIdFromRequest();

        if (!$tripId) {
            throw new RuntimeException('Trip ID is required.');
        }

        $errors = $this->getSessionErrors();
        $old = $this->getSessionOldData();

        if (empty($errors) && empty($old)) {
            $old = $this->fetchTripData($tripId);
        }

        $this->clearSessionData();

        $this->view->render('edit_trip', [
            'title' => 'Edit trip',
            'difficulties' => $this->model->getAllDifficulties(),
            'statuses' => $this->model->getAllStatuses(),
            'inventories' => (new Inventory())->getAllInventory(),
            'old' => $old,
            'errors' => $errors
        ]);
    }

    public function update(): void
    {
        $data = Helpers::getPostData($this->fields);
        $data['id'] = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $inventory = filter_input(INPUT_POST, 'inventory', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        $errors = $this->validateData($data);
        $this->handlePhotoUploadErrors($errors);

        if (!empty($errors)) {
            $this->saveSessionData($data, $errors);
            Route::redirect('/index/edit');
        }

        $data['photo'] = $this->processPhotoUpload($data);
        $data['user_id'] = 1;

        $this->updateTrip($data);

        $this->storeTripInventory($data['id'], $inventory);

        $this->session->trip_id = $data['id'];
        Route::redirect('/index/show');
    }

    public function delete(): void
    {
        $tripId = filter_input(INPUT_POST, 'trip_id', FILTER_VALIDATE_INT);

        $trip = $this->fetchTripData($tripId);
        if (!$trip) {
            throw new RuntimeException('Trip not found.');
        }

        $this->deleteTrip($trip);
        Route::redirect();
    }
    public function like(): void
    {
        $tripId = filter_input(INPUT_POST, 'trip_id', FILTER_VALIDATE_INT);

        if ($tripId) {
            $this->toggleLike($tripId);
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? '/index/index';
        if (strpos($referer, '/index/show') !== false && $tripId) {
            $this->session->trip_id = $tripId;
        }

        Route::redirect($referer);
    }
    private function getTripIdFromRequest(): ?int
    {
        return filter_input(INPUT_POST, 'trip_id', FILTER_VALIDATE_INT) ?? $this->session->old['id'] ?? null;
    }

    private function getSessionErrors(): array
    {
        return $this->session->errors ?? [];
    }

    private function getSessionOldData(): array
    {
        return $this->session->old ?? [];
    }

    private function fetchTripData(int $tripId): array
    {
        $trip = $this->model->getById('trips', 'id', $tripId);
        if (!$trip) {
            throw new RuntimeException('Trip not found.');
        }
        return $trip;
    }

    private function clearSessionData(): void
    {
        $this->session->remote('old');
        $this->session->remote('errors');
    }

    private function validateData(array $data): array
    {
        return $this->validator->validate($data);
    }

    private function handlePhotoUploadErrors(array &$errors): void
    {
        if (!empty($_FILES['photo']) && $_FILES['photo']['error'] != UPLOAD_ERR_OK) {
            $errors['photo'] = 'Error occurred during upload';
        }
    }

    private function saveSessionData(array $data, array $errors): void
    {
        $this->session->old = $data;
        $this->session->errors = $errors;
    }

    private function processPhotoUpload(array $data): string
    {
        if (!empty($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $oldTrip = $this->fetchTripData($data['id']);
            $this->deleteOldPhoto($oldTrip['photo']);

            return Helpers::savePhoto($this->fileDir, $_FILES['photo']);
        }

        $trip = $this->fetchTripData($data['id']);
        return $trip['photo'];
    }

    private function deleteOldPhoto(string $photoPath): void
    {
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . $photoPath;

        if (file_exists($fullPath)) {
            if (!unlink($fullPath)) {
                throw new RuntimeException('Failed to delete old photo: ' . $fullPath);
            }
        } else {
            error_log('Photo does not exist: ' . $fullPath);
        }
    }

    private function updateTrip(array $data): void
    {
        if (!$this->model->update($data)) {
            throw new RuntimeException('Failed to update trip in the database.');
        }
        error_log("Trip updated with photo: " . $data['photo']);
    }

    private function deleteTrip(array $trip): void
    {
        if (!$this->model->delete($trip['id'])) {
            throw new RuntimeException('Failed to delete trip.');
        }
    }

    private function enrichTrips(?array $trips): array
    {
        if (!$trips) {
            return [];
        }
        foreach ($trips as &$trip) {
            $trip['status'] = $this->model->getStatusById($trip['status_id'])['name'];
            $trip['difficulty'] = $this->model->getDifficultyById($trip['difficulty_id'])['name'];
            $trip['likes'] = $this->model->countLikes($trip['id']);
        }
        unset($trip);
        return $trips;
    }

    private function getEnrichedTrip(int $tripId): ?array
    {
        $trip = $this->model->getById('trips', 'id', $tripId);
        return $trip ? $this->enrichTrips([$trip])[0] : null;
    }

    private function enrichInventoryWithParams(?array $inventoryIds): array
    {
        if (empty($inventoryIds)) {
            return [];
        }

        return array_filter(array_map(function ($id) {
            return $this->model->getById('inventory', 'id', $id);
        }, $inventoryIds));
    }

    private function storeTripInventory(int $tripId, ?array $inventory): void
    {
        if (empty($inventory)) {
            return;
        }

        foreach ($inventory as $inventoryId) {
            $this->model->createTripInventory([
                'trip_id' => $tripId,
                'inventory_id' => (int) $inventoryId,
            ]);
        }
    }

    private function toggleLike(int $tripId): void
    {
        $this->model->checkLike($tripId, 1)
            ? $this->model->addLike($tripId, 1)
            : $this->model->deleteLike($tripId, 1);
    }

    public function getPhotosForTrip(int $tripId): array
    {
        $galleryModel = new Gallery();
        return $galleryModel->getPhotosByTripId($tripId);
    }
}
