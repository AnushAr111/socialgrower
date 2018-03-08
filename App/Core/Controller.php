<?php

namespace App\Core;

use ReflectionClass;

class Controller
{
    protected $layout;

    public function redirect($to)
    {
        $redirect = new Redirect();
        $redirect->to = $to;
        return $redirect;
    }

    public function render($file, $vars = [])
    {
        $view = new View();
        $view->layout = $this->layout;
        $view->controller = str_replace('Controller', '', (new ReflectionClass($this))->getShortName());
        $view->view = $file . '.php';
        $view->vars = $vars;

        return $view;
    }
}