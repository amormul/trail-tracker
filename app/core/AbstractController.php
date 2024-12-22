<?php

namespace app\core;


use app\models\User;

abstract class AbstractController
{
    public View $view;
    protected $models = [];

    public function __construct()
    {
        $this->view = new View();

    }

    protected function loadModel(string $name) : void
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
        if(method_exists($this, $getterName)) {
            return $this->$getterName($params[1]);
        }
        return null;
    }
    protected function get_model(string $name)
    {
        if(isset($this->models[$name])) {
            return $this->models[$name];
        }
        return null;
    }


}