<?php

namespace app\controllers;

use app\core\AbstractController;
use app\core\Helpers;
use app\core\Route;
use app\core\Session;
use app\models\Gallery;

class GalleryController extends AbstractController
{
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
}