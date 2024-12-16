<?php

namespace app\controllers;

use app\core\AbstractController;

class IndexController extends AbstractController
{
    /**
     * displaying index.php
     * @return void
     */
    public function index(): void
    {
        $this->view->render('index', [
            'title' => 'All trips',
        ]);
    }

    /**
     * Renders add_trip.php page
     * @return void
     */
    public function create(): void
    {
        $this->view->render('add_trip', [
            'title' => 'Create trip',
        ]);
    }

    /**
     * Renders trip.php page
     * @return void
     */
    public function show(): void
    {
        $this->view->render('trip', [
            'title' => 'Trip page',
        ]);
    }
}
