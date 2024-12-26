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

class IndexController extends AbstractController
{
    protected Trip $model;
    protected Session $session;
    protected TripValidator $validator;

    private string $fileDir = 'storage' . DIRECTORY_SEPARATOR . 'imageTrip';
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
    /**
     * Display all trips.
     *
     * @return void
     */
    public function index(): void
    {
        $trips = $this->enrichTrips($this->model->getAll());
        $this->view->render('index', [
            'title' => 'All trips',
            'trips' => $trips
        ]);
    }

    /**
     * Show details for a specific trip.
     *
     * @return void
     */
    public function show(): void
    {
        $tripId = $this->getTripIdFromRequest();
        $trip = $this->getEnrichedTrip($tripId);

        $this->view->render('trip', [
            'title' => 'Trip page',
            'trip' => $trip,
            'route' => $this->model->getById('routes', 'trip_id', $trip['id']),
            'inventories' => $this->getEnrichedInventory($this->model->getInventoryByTrip($tripId))
        ]);
    }

    /**
     * Show the trip creation form.
     *
     * @return void
     */
    public function create(): void
    {
        $this->renderForm('add_trip', 'Create trip');
    }

    /**
     * Store a new trip in the database.
     *
     * @return void
     */
    public function store(): void
    {
        $data = Helpers::getPostData($this->fields);
        $inventory = filter_input(INPUT_POST, 'inventory', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        $errors = $this->validator->validate($data);
        $this->validator->validatePhotoUpload($errors);

        if ($errors) {
            $data['inventory'] = $inventory;
            $this->saveSessionData($data, $errors);
            Route::redirect('/index/create');
        }

        $data['photo'] = Helpers::savePhoto($this->fileDir, $_FILES['photo']);
        $data['user_id'] = $this->getCurrentUserId();
        $tripId = $this->model->create($data);

        if (!$tripId) {
            throw new RuntimeException('Failed to create trip.');
        }

        $this->storeTripInventory($tripId, $inventory);
        $this->session->trip_id = $tripId;
        Route::redirect('/index/show');
    }

    /**
     * Show the trip editing form.
     *
     * @return void
     */
    public function edit(): void
    {
        $tripId =  $this->getTripIdFromRequest();
        $this->session->trip_id = $tripId;
        $this->renderForm('edit_trip', 'Edit trip', $tripId);
    }

    /**
     * Update an existing trip.
     *
     * @return void
     */
    public function update(): void
    {
        $data = Helpers::getPostData($this->fields);
        $data['id'] = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $inventory = filter_input(INPUT_POST, 'inventory', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $errors = $this->validator->validate($data);

        if ($errors) {
            $data['inventory'] = $inventory;
            $this->saveSessionData($data, $errors);
            Route::redirect('/index/edit');
        }

        $data['photo'] = $this->processPhotoUpload($data);

        $this->model->update($data);
        $this->storeTripInventory($data['id'], $inventory);

        $this->session->trip_id = $data['id'];
        Route::redirect('/index/show');
    }

    /**
     * Delete a trip.
     *
     * @return void
     */
    public function delete(): void
    {
        $tripId = $this->getTripIdFromRequest();
        $oldPhoto = $this->model->getById('trips', 'id', $tripId)['photo'];

        if (!$this->model->delete($tripId)) {
            throw new RuntimeException('Failed to delete trip.');
        }
        Helpers::deletePhoto($oldPhoto);

        Route::redirect('/index/index');
    }

    /**
     * Toggle like status for a trip.
     *
     * @return void
     */
    public function like(): void
    {
        $tripId = $this->getTripIdFromRequest();
        if ($tripId) {
            $this->model->checkLike($tripId, $this->getCurrentUserId())
                ? $this->model->addLike($tripId, $this->getCurrentUserId())
                : $this->model->deleteLike($tripId, $this->getCurrentUserId());
        }

        $this->session->trip_id = $tripId;
        Route::redirect($_SERVER['HTTP_REFERER'] ?? '/index/index');
    }

    /**
     * Store inventory data for a trip.
     *
     * @param int $tripId The ID of the trip.
     * @param array|null $inventory The inventory data.
     * @return void
     */
    private function storeTripInventory(int $tripId, ?array $inventory): void
    {
        if (!$inventory) return;

        foreach ($inventory as $inventoryId) {
            $this->model->createTripInventory([
                'trip_id' => $tripId,
                'inventory_id' => $inventoryId
            ]);
        }
    }

    public function deleteInventory(): void
    {
        $inventoryId = filter_input(INPUT_POST, 'inventory_id', FILTER_VALIDATE_INT);
        $tripId = filter_input(INPUT_POST, 'trip_id', FILTER_VALIDATE_INT);
        $this->model->deleteTripInventory($tripId, $inventoryId);
        $this->session->trip_id = $tripId;
        Route::redirect('/index/show');
    }

    /**
     * Add a new status for trips.
     *
     * @return void
     */
    public function addStatus(): void
    {
        $data['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$this->model->createStatus($data)) {
            throw new RuntimeException('Status not created');
        }

        Route::redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Add a new difficulty for trips.
     *
     * @return void
     */
    public function addDifficulty(): void
    {
        $data['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['description'] = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$this->model->createDifficult($data)) {
            throw new RuntimeException('Difficulty not created');
        }

        Route::redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Retrieve the trip ID from the request.
     *
     * @return int|null The trip ID or null if not found.
     */
    private function getTripIdFromRequest(): ?int
    {
        return filter_input(INPUT_POST, 'trip_id', FILTER_VALIDATE_INT) ?? $this->session->trip_id;
    }

    /**
     * Render a form view.
     *
     * @param string $view The view name.
     * @param string $title The title of the form.
     * @param int|null $tripId The ID of the trip (optional).
     * @return void
     */
    private function renderForm(string $view, string $title, ?int $tripId = null): void
    {
        $errors = $this->session->errors ?? [];
        $old = $this->session->old ?? ($tripId ? $this->model->getById('trips', 'id', $tripId) : []);

        $currentInventory = $tripId ? $this->getEnrichedInventory($this->model->getInventoryByTrip($tripId)) : [];
        $currentInventoryIds = array_column($currentInventory, 'id');

        $selectedInventory = isset($old['inventory'])
            ? array_unique(array_merge($currentInventoryIds, $old['inventory']))
            : $currentInventoryIds;

        $this->clearSessionData();

        $this->view->render($view, [
            'title' => $title,
            'difficulties' => $this->model->getAllDifficulties(),
            'statuses' => $this->model->getAllStatuses(),
            'inventories' => (new Inventory())->getAllInventory(),
            'selectedInventory' => $selectedInventory,
            'old' => $old,
            'errors' => $errors
        ]);
    }


    /**
     * Handle photo upload for trips.
     *
     * @param array $data The trip data.
     * @return string The path to the photo.
     */
    private function processPhotoUpload(array $data): string
    {
        if (!empty($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $fullPath = $this->model->getById('trips', 'id', $data['id'])['photo'];
            Helpers::deletePhoto($fullPath);
            return Helpers::savePhoto($this->fileDir, $_FILES['photo']);
        }

        return $this->model_trip->getById($data['id'])['photo'];
    }

    /**
     * Enrich a list of trips with additional data.
     *
     * @param array|null $trips The list of trips.
     * @return array The enriched trips.
     */
    private function enrichTrips(?array $trips): array
    {
        if (!$trips) return [];

        return array_map(function ($trip) {
            $trip['status'] = $this->model->getStatusById($trip['status_id'])['name'];
            $trip['difficulty'] = $this->model->getDifficultyById($trip['difficulty_id'])['name'];
            $trip['likes'] = $this->model->countLikes($trip['id']);
            $trip['user'] = $this->model->getById('users', 'id', $trip['user_id'])['login'];
            return $trip;
        }, $trips);
    }

    /**
     * Enrich a single trip with additional data.
     *
     * @param int $tripId The ID of the trip.
     * @return array The enriched trip.
     * @throws RuntimeException If the trip is not found.
     */
    private function getEnrichedTrip(int $tripId): array
    {
        $trip = $this->model->getById('trips', 'id', $tripId);
        if (!$trip) {
            throw new RuntimeException('Trip not found.');
        }
        return $this->enrichTrips([$trip])[0];
    }

    /**
     * Enrich inventory data for a trip.
     *
     * @param array|null $inventoryIds The list of inventory IDs.
     * @return array The enriched inventory data.
     */
    private function getEnrichedInventory(?array $inventoryIds): array
    {
        if (!$inventoryIds) return [];

        return array_filter(array_map(function ($id) {
            return $this->model->getById('inventory', 'id', $id);
        }, $inventoryIds));
    }

    /**
     * Save session data for form handling.
     *
     * @param array $data The form data.
     * @param array $errors The validation errors.
     * @return void
     */
    private function saveSessionData(array $data, array $errors): void
    {
        $this->session->old = $data;
        $this->session->errors = $errors;
    }

    /**
     * Clear session data used for form handling.
     *
     * @return void
     */
    private function clearSessionData(): void
    {
        $this->session->remote('old');
        $this->session->remote('errors');
    }
}
