<?php

namespace app\controllers;

use Exception;

class RouteController extends \app\core\AbstractController
{
    public function __construct()
    {
        parent::__construct();
        try {
            $this->loadModel('route');
            $this->loadModel('user');
        } catch (Exception $e) {
            $this->outputException($e->getMessage());
        }
    }

    /**
     * reading the input data of the route
     * @return array
     */
    public function inputData(): array
    {
        return [
            'trip_id' => filter_input(INPUT_POST, 'trip_id', FILTER_VALIDATE_INT),
            'description' => filter_input(INPUT_POST, 'route_description'),
            'photo' => $_FILES['route_photo'],
            'file' => filter_input(INPUT_POST, 'current_photo'),
        ];
    }

    /**
     * log entry about a specific error and show the page error.php
     * @param string $message
     * @return void
     */
    public function outputException(string $message): void
    {
        //    запис в log про конкретну помилку
        \app\core\Logs::write($message);
        //    показати сторінку що щось пішло не так
        $this->view->render('error', ['title' => 'oops', 'message' => $message]);
    }

    /**
     * Renders the add_route.php page
     * @return void
     */
    public function add_route(): void
    {
        $errors = $this->session->errors_route ?? [];
        $this->session->remote('errors_route');

        $trip_id = filter_input(INPUT_POST, 'trip_id', FILTER_VALIDATE_INT);
        $this->view->render('add_route', [
            'title' => 'Add Route',
            'trip_id' => $trip_id,
            'errors' => $errors,
            'login' => $this->login,
        ]);
    }
    /**
     * creates route  in DB
     * @return void
     * @throws \Exception
     */
    public function store(): void
    {
        $data = $this->inputData();
        $res = \app\core\RouteValidators::validateTrip($data['trip_id']);
        if (!$res) {
            $this->outputException('Missing from the database trip ' .  $data['trip_id']);
        } else {
            $errors = \app\core\RouteValidators::validateRoute($data);
            $this->session->trip_id = $data['trip_id'];
            if (!empty($errors)) {
                $this->session->errors_route = $errors;
                \app\core\Route::redirect('/route/add_route');
            } else {
                try {
                    $route = $this->model_route->getWhere('trip_id', 'user_id', 'i');
                    if (empty($route)) {
                        $this->model_route->create($data);
                    } else {
                        $this->model_route->update($data);
                    }
                } catch (Exception $e) {
                    $this->outputException($e->getMessage());
                }
                \app\core\Route::redirect('/index/show');
            }
        }
    }

    /**
     * Renders the update_route.php page
     * @return void
     */
    public function edit_route(): void
    {
        $trip_id = filter_input(INPUT_POST, 'trip_id', FILTER_VALIDATE_INT);
        $route = $this->model_route->getRouteByTripId($trip_id);
        if (empty($route)) {
            $route['trip_id'] = $trip_id;
        }
        $exist_photo = true;
        if (empty($route['photo'])) {
            $route['photo'] = "/images/add.png";
            $exist_photo = false;
        }
        $errors = $this->session->errors_route ?? [];
        $this->session->remote('errors_route');
        $this->view->render('update_route', [
            'title' => 'Update Route',
            'route' => $route,
            'exist_photo' => $exist_photo,
            'errors' => $errors,
            'login' => $this->login,
        ]);
    }
    /**
     * updates  route in DB;
     * @return void
     * @throws \Exception
     */
    public function update(): void
    {
        $data = $this->inputData();
        $res = \app\core\RouteValidators::validateTrip($data['trip_id']);
        if (!$res) {
            $this->outputException('Missing from the database trip ' .  $data['trip_id']);
        } else {
            $this->session->trip_id = $data['trip_id'];
            $errors = \app\core\RouteValidators::validateRoute($data);
            if (!empty($errors)) {
                $this->session->errors_route = $errors;
                \app\core\Route::redirect('/route/edit_route');
            } else {
                try {
                    $route = $this->model_route->getRouteByTripId($data['trip_id']);
                    if (empty($route)) {
                        $this->model_route->create($data);
                    } else {
                        $this->model_route->update($data);
                    }
                } catch (Exception $e) {
                    $this->outputException($e->getMessage());
                }
                \app\core\Route::redirect('/index/show');
            }
        }
    }

    /**
     * creates or deletes like record in DB
     * @return void
     */
    public function like(): void
    {
        $trip_id = filter_input(INPUT_POST, 'trip_id');
        $res = \app\core\RouteValidators::validateTrip($trip_id);
        if (!$res) {
            $this->outputException('Missing from the database trip ' .  $trip_id);
        } else {
            if (empty($this->login)) {
                $this->session->errors_route = 'User not found';
                \app\core\Route::redirect('/index/show');
            }
            $user = $this->model_user->getByLogin($this->login);
            $user_id = $user['id'];
            $route = $this->model_route->getRouteByTripId($trip_id);
            $this->session->trip_id = $trip_id;
            try {
                $res = $this->model_route->like($route['id'], $user_id);
            } catch (Exception $e) {
                $this->outputException($e->getMessage());
            }
            \app\core\Route::redirect('/index/show');
        }
    }
}
