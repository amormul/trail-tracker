<?php

namespace app\controllers;

use app\core\AbstractController;
use app\core\Route;
use app\core\Session;
use app\models\Inventory;
use app\models\Trip;

class IndexController extends AbstractController
{
    protected Trip $model;
    protected Session $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = new Session();
        $this->model = new Trip();
    }

    /**
     * Renders the index.php page with a list of all trips.
     * @return void
     */
    public function index(): void
    {
        $trips = $this->enrichTripWithParams($this->model->getAll());
        $this->view->render('index', [
            'title' => 'All trips',
            'trips' => $trips
        ]);
    }

    /**
     * Renders the add_trip.php page with required data for creating a new trip.
     * @return void
     */
    public function create(): void
    {
        $difficulties = $this->model->getAllDifficulties();
        $statuses = $this->model->getAllStatuses();
        $inventories = (new Inventory())->getAllInventory();
        $this->view->render('add_trip', [
            'title' => 'Create trip',
            'difficulties' => $difficulties,
            'statuses' => $statuses,
            'inventories' => $inventories
        ]);
    }

    public function store(): void
    {
        /** TODO
         * Get all data from POST and FILES
         * Validate
         * Create dependent entities
         * Save photo in FS
         * Create trip
         * Store old data to session->old
         * Redirect to /route/store
         */
    }

    /**
     * Renders the trip.php page for a specific trip.
     * @return void
     */
    public function show(): void
    {
        $trip_id = filter_input(INPUT_POST, 'trip_id', FILTER_VALIDATE_INT) ?: $this->session->trip_id;

        $trip = $this->model->getById('trips', 'id', $trip_id);

        $trip = $trip ? $this->enrichTripWithParams([$trip])[0] : null;

        $this->view->render('trip', [
            'title' => 'Trip page',
            'trip' => $trip
        ]);
    }

    /**
     * Handles liking or unliking a trip by a user.
     * Redirects the user to the referer or a default page.
     * @return void
     */
    public function like(): void
    {
        $trip_id = filter_input(INPUT_POST, 'trip_id', FILTER_VALIDATE_INT);

        if ($trip_id) {
            $this->model->checkLike($trip_id, 1)
                ? $this->model->addLike($trip_id, 1)
                : $this->model->deleteLike($trip_id, 1);
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? '/index/index';

        if (strpos($referer, '/index/show') !== false && $trip_id) {
            $this->session->trip_id = $trip_id;
        }

        Route::redirect($referer);
    }

    /**
     * Enriches a list of trips with additional parameters such as status, difficulty, and likes.
     * @param array $trips Array of trips to enrich.
     * @return array Enriched array of trips.
     */
    private function enrichTripWithParams(array $trips): array
    {
        foreach ($trips as &$trip) {
            $trip['status'] = $this->model->getStatusById($trip['status_id'])['name'];
            $trip['difficulty'] = $this->model->getDifficultyById($trip['difficulty_id'])['name'];
            $trip['likes'] = $this->model->countLikes($trip['id']);
        }

        unset($trip);
        return $trips;
    }
}
