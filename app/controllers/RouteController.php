<?php

use app\core\Session;
use app\models\Route;

class RouteController extends \app\core\AbstractController
{
    protected Route $route;

    public function __construct()
    {
        parent::__construct();
        $this->route = new Route();
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
            $session = new Session();
            $session->errors = $errors;
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
            $session = new Session();
            $session->errors = $errors;
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
            \app\core\Route::redirect('/index/show');
        }
    }

    /**
     * deletes route from DB;
     * @return void
     */
    public function delete(): void
    {
        $trip_id = (int)filter_input(INPUT_POST, 'trip_id');
        $this->route->delete($trip_id);

    }

    /**
     * creates like record in DB
     * @return void
     */
    public function addLike(): void
    {
        $route_id = (int)filter_input(INPUT_POST, 'route_id');
        $user_id = $this->getCurrentUserId();
        $res = $this->route->addLike($route_id, $user_id);
        if (!$res) {
            $error = 'error adding route like in database';
            \app\core\Logs::write($error);
            //    показати сторінку що щось пішло не так
            $this->view->render('error', ['title' => 'oops', 'message' => $error]);
        }
        \app\core\Route::redirect('/index/show');
    }

    /**
     * deletes like record from DB;
     * @return void
     */
    public function deleteLike(): void
    {
        $route_id = (int)filter_input(INPUT_POST, 'route_id');
        $user_id = $this->getCurrentUserId();
        $res = $this->route->deleteLike($route_id, $user_id);
        if (!$res) {
            $error = 'error deleting route like in database';
            \app\core\Logs::write($error);
            //    показати сторінку що щось пішло не так
            $this->view->render('error', ['title' => 'oops', 'message' => $error]);
        }
        \app\core\Route::redirect('/index/show');
    }
}