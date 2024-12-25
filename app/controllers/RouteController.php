<?php

namespace app\controllers;

use app\core\Session;
use app\models\Route;

class RouteController extends \app\core\AbstractController
{
    protected Route $route;
    protected Session $session;

    public function __construct()
    {
        parent::__construct();
        try{
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
            'trip_id' => filter_input(INPUT_POST, 'trip_id',FILTER_VALIDATE_INT) ,
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
    public function outputException(string $message) : void
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
        $trip_id = filter_input(INPUT_POST, 'trip_id',FILTER_VALIDATE_INT);
        $this->view->render('add_route', [
            'title' => 'Add Route',
            'trip_id' => $trip_id,
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
        $res = \app\core\RouteValidators::validateTrip( $data['trip_id']);
        if (!$res){
            $this->outputException('Missing from the database trip ' .  $data['trip_id']);
        }else {
            $errors = \app\core\RouteValidators::validateRoute($data);
            $this->session->trip_id = $data['trip_id'];
            if (!empty($errors)) {
                $this->view->render('add_route', [
                    'title' => 'Add Route',
                    'trip_id' => $data['trip_id'],
                    'errors' => $errors,
                ]);
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
        $trip_id = filter_input(INPUT_POST, 'trip_id',FILTER_VALIDATE_INT);
        $route = $this->model_route->getWhere('trip_id',$trip_id,'i');
        $exist_photo = true;
        if (empty($route['photo'])) {
            $route['photo'] = "/images/add.png";
            $exist_photo = false;
        }
        $this->view->render('update_route', [
            'title' => 'Update Route',
            'route' => $route,
            'exist_photo' => $exist_photo,
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
        $res = \app\core\RouteValidators::validateTrip( $data['trip_id']);
        if (!$res){
            $this->outputException('Missing from the database trip ' .  $data['trip_id']);
        }else {
            $this->session->trip_id = $data['trip_id'];
            $errors = \app\core\RouteValidators::validateRoute($data);
            if (!empty($errors)) {
                $route = $this->model_route->getWhere('trip_id', $data['trip_id'], 'i');
                $this->view->render('update_route', [
                    'title' => 'Update Route',
                    'route' => $route,
                    'errors' => $errors,
                ]);
            } else {
                try {
                    $this->model_route->update($data);
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
        $res = \app\core\RouteValidators::validateTrip( $trip_id);
        if (!$res){
            $this->outputException('Missing from the database trip ' .  $trip_id);
        }else {
            $user = $this->model_user->getByLogin($this->session->login);
            $user_id = $user['id'];
            $route = $this->model_route->getWhere('trip_id', $trip_id, 'i');
            $this->session->trip_id = $trip_id;
            try {
                $res = $this->model_route->like($route['id'], $user_id);
            } catch (\Exception $e) {
                $this->outputException($e->getMessage());
            }
            \app\core\Route::redirect('/index/show');
        }
    }
}