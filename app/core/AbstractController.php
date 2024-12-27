<?php

namespace app\core;


use app\models\User;

abstract class AbstractController
{
    public View $view;
    protected Session $session;
    protected $models = [];
    protected $login;

    public function __construct()
    {
        $this->view = new View();
        $this->session = new Session();
        $this->login = $this->session->login ?? null;
    }

    /**
     * @param string $name
     * @return void
     * @throws \Exception
     */
    protected function loadModel(string $name): void
    {
        $modelClass = 'app\models\\' . ucfirst($name);
        if (!class_exists($modelClass)) {
            throw new \Exception('');
        }
        $this->models[$name] = new $modelClass;
    }

    public function __get(string $name)
    {
        $params = explode('_', $name);
        $getterName = 'get_' . ucfirst($params[0]);
        if (method_exists($this, $getterName)) {
            return $this->$getterName($params[1]);
        }
        return null;
    }

    /**
     * @param string $name
     * @return object|null
     */
    protected function get_model(string $name): ?object
    {
        if (isset($this->models[$name])) {
            return $this->models[$name];
        }
        return null;
    }

    /**
     * Get id of logged in user
     * @return int
     */
    public function getCurrentUserId(): int
    {
        $userModel = new User();
        $user = $userModel->getByLogin($this->login ?? '');
        if(empty($user)){
            return 0;
        }
        return (int)$user['id'];
    }

    /**
     * Check if the logged user is author
     * @param int $tripId
     * @return bool
     */
    public function isAuthor(int $tripId): bool
    {
        return $this->getCurrentUserId() === $this->model->getById('trips', 'id', $tripId)['user_id'];
    }
}
