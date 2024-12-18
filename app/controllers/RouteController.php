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
     * creates route  in DB
     * @return void
     * @throws \Exception
     */
    public function store(): void
    {
        $data = [
            'trip_id' => (int)filter_input(INPUT_POST, 'trip_id'),
            'description' => filter_input(INPUT_POST, 'description'),
            'photo' => filter_input(INPUT_POST, 'photo'),
        ];
        $errors = \app\core\RouteValidators::validateRoute($data);
        $this->session->trip_id = $data['trip_id'];
        if (!empty($errors)) {
            $this->session->errors = $errors;
            \app\core\Route::redirect('/index/add');
        }else{
            try {
                $res = $this->route->create($data);
            } catch (Exception $e) {
                \app\core\Logs::write($e->getMessage());
                //    показати сторінку що щось пішло не так
                $this->view->render('error', ['title' => 'oops', 'message' => $e->getMessage()]);
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
        $data = [
            'trip_id' => (int)filter_input(INPUT_POST, 'trip_id'),
            'description' => filter_input(INPUT_POST, 'description'),
            'photo' => filter_input(INPUT_POST, 'photo'),
        ];
        $errors = \app\core\RouteValidators::validateRoute($data);
        $this->session->trip_id = $data['trip_id'];
        if (!empty($errors)) {
            $this->session->errors = $errors;
            \app\core\Route::redirect('/index/edit');
        }else{
            try {
                $res = $this->route->update($data);
            } catch (Exception $e) {
                //    запис в log про конкретну помилку
                \app\core\Logs::write($e->getMessage());
                //    показати сторінку що щось пішло не так
                $this->view->render('error', ['title' => 'oops', 'message' => $e->getMessage()]);
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
        $trip_id = (int)filter_input(INPUT_POST, 'trip_id');
        $user_id = $this->getCurrentUserId();
        $route = $this->route->getByTripId($trip_id);
        $this->session->trip_id = $trip_id;
        try {
            $res = $this->route->like($route['id'], $user_id);
        } catch (\Exception $e){
            //    запис в log про конкретну помилку
            \app\core\Logs::write($e->getMessage());
            //    показати сторінку що щось пішло не так
            $this->view->render('error', ['title' => 'oops', 'message' => $e->getMessage()]);
        }
        \app\core\Route::redirect('/index/show');
    }
}