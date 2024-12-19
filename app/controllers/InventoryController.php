<?php
namespace app\controllers;

use app\core\AbstractController;
use app\core\Route;
use app\core\Session;
use app\models\Inventory;
class InventoryController extends AbstractController
{
    protected Inventory $model;
    protected Session $session;
    public function __construct()
    {
        parent::__construct();
        $this->session = new Session();
        $this->model = new Inventory();
    }

    /**
     * Renders the inventory list page
     * @return void
     */
    public function index(): void
    {
        $inventory = $this->model->getAllInventory();
        $this->view->render('inventory', [
            'title' => 'Inventory List',
            'inventories' => $inventory
        ]);
    }
    /**
     * Renders the page to create a new inventory item.
     * @return void
     */
    public function create(): void
    {
        $this->view->render('add_inventory', [
            'title' => 'Create Inventory Item'
        ]);
    }

    /**
     * Stores a new inventory item in the database.
     * @return void
     */
    public function store(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name');
        $description = filter_input(INPUT_POST, 'description');
        $photo = $_FILES['photo'] ?? null;

        if ($id && $name && $description) {
            $data = [
                'id' => $id,
                'name' => $name,
                'description' => $description
            ];
            $photoPath = $this->savePhoto($photo);
            $data['photo'] = $photoPath;

            if ($this->model->create($data)) {
                Route::redirect('/inventory');
            }
        }
        Route::redirect('/dda_inventory/' . ($data['id'] ?? ''));
    }

    /**
     * Renders the edit page for a specific inventory item.
     * @param int $id
     * @return void
     */
    public function edit(int $id): void
    {
        $inventory = $this->model->getById('inventory', 'id', $id);
        $this->view->render('update_inventory', [
            'title' => 'Edit Inventory Item',
            'inventory' => $inventory
        ]);
    }

    /**
     * Updates an existing inventory item in the database.
     * @return void
     */
    public function update(): void
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name');
        $description = filter_input(INPUT_POST, 'description');
        $photo = $_FILES['photo'] ?? null;
        if ($id && $name && $description) {
            $data = [
                'id' => $id,
                'name' => $name,
                'description' => $description
            ];
            if ($photo) {
                $data['photo'] = $this->savePhoto($photo);
            }
            if ($this->model->update($data)) {
                Route::redirect('/inventory');
            }
        }
        Route::redirect('/update_inventory/' . ($data['id'] ?? ''));
    }


    /**
     * Deletes an inventory item from the database.
     * @param int $id
     * @return void
     */
    public function delete(): void
    {
        $id = filter_input(INPUT_POST, 'id');

        //TODO: validate

        $this->model->delete($id);
        Route::redirect('/inventory');
    }

    /**
     * Saves an uploaded photo to the filesystem.
     * @param array $photo
     * @return string|null
     */
    private function savePhoto(array $photo): ?string
    {
        $uploadDir = 'uploads/inventory/';
        $uploadFile = $uploadDir . basename($photo['name']);
        if (move_uploaded_file($photo['tmp_name'], $uploadFile)) {
            return $uploadFile;
        }
        return null;
    }
}