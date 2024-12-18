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
        $this->route = new Route();
        $this->session = new Session();
    }

    /**
     * reading the input data of the route
     * @return array
     */
    public function inputData(): array
    {
        return [
            'trip_id' => (int)filter_input(INPUT_POST, 'trip_id',FILTER_VALIDATE_INT) ?:
                (int)$this->session->old['trip_id'],
            'description' => filter_input(INPUT_POST, 'route_description') ?:
                $this->session->old['route_description'],
            'photo' => $_FILES['route_photo'],
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
     * creates route  in DB
     * @return void
     * @throws \Exception
     */
    public function store(): void
    {
        $data = $this->inputData();
        $errors = \app\core\RouteValidators::validateRoute($data);
        $this->session->trip_id = $data['trip_id'];
        if (!empty($errors)) {
            $this->session->errors = $errors;
            \app\core\Route::redirect('/index/add');
        }else{
            try {
                $res = $this->route->create($data);
            } catch (Exception $e) {
                $this->outputException($e->getMessage());
            }
            \app\core\Route::redirect('/index/show');
        }
    }

    /**
     * updates  route in DB;
     * @return void
     * @throws \Exception
     */
    public function update(): void
    {
        $data = $this->inputData();
        $errors = \app\core\RouteValidators::validateRoute($data);
        $this->session->trip_id = $data['trip_id'];
        if (!empty($errors)) {
            $this->session->errors = $errors;
            \app\core\Route::redirect('/index/edit');
        }else{
            try {
                $res = $this->route->update($data);
            } catch (Exception $e) {
                $this->outputException($e->getMessage());
            }
            \app\core\Route::redirect('/index/show');
        }
    }

    /**
     * creates or deletes like record in DB
     * @return void
     */
    public function like(): void
    {
        $trip_id = (int)filter_input(INPUT_POST, 'trip_id')?:
            (int)$this->session->old['trip_id'];
        $user_id = $this->getCurrentUserId();
        $route = $this->route->getByTripId($trip_id);
        $this->session->trip_id = $trip_id;
        try {
            $res = $this->route->like($route['id'], $user_id);
        } catch (\Exception $e){
            $this->outputException($e->getMessage());
        }
        \app\core\Route::redirect('/index/show');
    }
}