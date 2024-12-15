<?php

namespace app\core;
class View
{
    const VIEWS_DIR = '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;

    public $template = 'default';

    public function __construct(string $template = null)
    {
        if($template){
            $this->template = $template;
        }
    }

    /**
     * Show front
     * @param string $page
     * @param array $data
     * @return void
     */
    public function render(string $page,array $data) : void
    {
        extract($data);
        include_once self::VIEWS_DIR . 'templates' . DIRECTORY_SEPARATOR . $this->template . '.php';
    }
}