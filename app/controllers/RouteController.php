<?php

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
     */
  public function store(): void
    {
        $data = [
            'trip_id' => (int)filter_input(INPUT_POST, 'trip_id'),
            'description' => filter_input(INPUT_POST, 'description'),
            'photo' => filter_input(INPUT_POST, 'photo'),
        ];
        $errors = \app\core\RouteValidators::validateRoute($data);
        if (!empty($errors)) {
            $this->session->errors = $errors;
            \app\core\Route::redirect('/index/add');
        }else{
            try {
                $res = $this->route->create($data);
                if (!$res) {
                    $error = 'error adding route to database';
                    \app\core\Logs::write($error);
                    //    показати сторінку що щось пішло не так
                    $this->view->render('error', ['title' => 'oops', 'message' => $error]);
                }
            } catch (Exception $e) {
                \app\core\Logs::write($e->getMessage());
                //    показати сторінку що щось пішло не так
                $this->view->render('error', ['title' => 'oops', 'message' => $e->getMessage()]);
            }
            $this->session->trip_id = $data['trip_id'];
            \app\core\Route::redirect('/index/show');
        }
    }

    /**
     * updates  route in DB;
     * @return void
     */
    public function update(): void
    {
        $data = [
            'trip_id' => (int)filter_input(INPUT_POST, 'trip_id'),
            'description' => filter_input(INPUT_POST, 'description'),
            'photo' => filter_input(INPUT_POST, 'photo'),
        ];
        $errors = \app\core\RouteValidators::validateRoute($data);
        if (!empty($errors)) {
            $this->session->errors = $errors;
            \app\core\Route::redirect('/index/edit');
        }else{
            try {
                $res = $this->route->update($data);
                if (!$res) {
                    $error = 'error updating route in database';
                    \app\core\Logs::write($error);
                    //    показати сторінку що щось пішло не так
                    $this->view->render('error', ['title' => 'oops', 'message' => $error]);
                }
            } catch (Exception $e) {
                //    запис в log про конкретну помилку
                \app\core\Logs::write($e->getMessage());
                //    показати сторінку що щось пішло не так
                $this->view->render('error', ['title' => 'oops', 'message' => $e->getMessage()]);
            }
            $this->session->trip_id = $data['trip_id'];
            \app\core\Route::redirect('/index/show');
        }
    }

    /**
     * creates like record in DB
     * @return void
     */
    public function addLike(): void
    {
        $trip_id = (int)filter_input(INPUT_POST, 'trip_id');
        $user_id = $this->getCurrentUserId();
        $route = $this->route->getByTripId($trip_id);
        $res = $this->route->addLike($route['id'], $user_id);
        if (!$res) {
            $error = 'error adding route like in database';
            \app\core\Logs::write($error);
            //    показати сторінку що щось пішло не так
            $this->view->render('error', ['title' => 'oops', 'message' => $error]);
        }
        $this->session->trip_id = $trip_id;
        \app\core\Route::redirect('/index/show');
    }

    /**
     * deletes like record from DB;
     * @return void
     */
    public function deleteLike(): void
    {
        $trip_id = (int)filter_input(INPUT_POST, 'trip_id');
        $user_id = $this->getCurrentUserId();
        $route = $this->route->getByTripId($trip_id);
        $res = $this->route->deleteLike($route['id'], $user_id);
        if (!$res) {
            $error = 'error deleting route like in database';
            \app\core\Logs::write($error);
            //    показати сторінку що щось пішло не так
            $this->view->render('error', ['title' => 'oops', 'message' => $error]);
        }
        $this->session->trip_id = $trip_id;
        \app\core\Route::redirect('/index/show');
    }
}