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
}
