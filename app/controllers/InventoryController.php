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
        $inventories = $this->model->getAllInventory();
        $inventories = $inventories ?: [];
        $this->view->render('inventory', [
            'title' => 'Inventory List',
            'inventories' => $inventories
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
        $name = filter_input(INPUT_POST, 'name');
        $description = filter_input(INPUT_POST, 'description');
        $photo = $_FILES['photo'] ?? null;

        if ($name && $description) {
            $inventory = [
                'name' => $name,
                'description' => $description
            ];
            $photoPath = $this->savePhoto($photo);
            $inventory['photo'] = $photoPath;

            if ($this->model->create($inventory)) {
                Route::redirect('/inventory');
            }
        }else{
            exit('error');
        }
    }

    /**
     * Renders the edit page for a specific inventory item.
     * @param int $id
     * @return void
     */
    public function edit(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
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
            if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
                $data['photo'] = $this->savePhoto($photo);
            } else {
            $existing = $this->model->getById('inventory', 'id', $id);
            $data['photo'] = $existing['photo'] ?? null;
        }
            if ($this->model->update($data)) {
                Route::redirect('/inventory');
                return;
            }
        }
        Route::redirect('/inventory/edit/?id=' . ($id ?? ''));
    }


    /**
     * Deletes an inventory item from the database.
     * @param int $id
     * @return void
     */
    public function delete(): void
    {
        $id = filter_input(INPUT_POST, 'id');
        if ((int)$id == 0) {
            exit('error 404');
        }
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
        $uploadDir = 'storage/imageInventory/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fileName = uniqid() . '_' . basename($photo['name']);
        $uploadFile = $uploadDir . $fileName;
        if (move_uploaded_file($photo['tmp_name'], $uploadFile)) {
            return $uploadFile;
        }
        return null;
    }

    /**
     * Displays the details of a specific inventory item.
     * @return void
     */
    public function show(): void
    {
        $inventory_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$inventory_id) {
            Route::redirect('/inventory');
            return;
        }

        $inventory = $this->model->getById('inventory', 'id', $inventory_id);

        if (!$inventory) {
            Route::redirect('/inventory');
            return;
        }

        $this->view->render('show_page', [
            'title' => 'Inventory Details',
            'inventory' => $inventory
        ]);
    }


}