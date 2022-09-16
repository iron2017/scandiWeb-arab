<?php

namespace App;

class Router
{
    private $handlers = [];

    public function get(string $path, string $controller): void
    {
        array_push($this->handlers, array($path, $controller, 'get'));
    }

    public function post(string $path, string $controller): void
    {
        array_push($this->handlers, array($path, $controller, 'post'));
    }

    public function check()
    {
        $key = array_search($_SERVER['REQUEST_URI'], array_column($this->handlers, 0));
        if ($key != false) {
            if ($this->handlers[$key][2] == 'get')
                return call_user_func($this->handlers[$key][1]);
            else {
                $inputs = json_decode(file_get_contents('php://input'));
                return call_user_func($this->handlers[$key][1], $inputs);
            }
        }
        return call_user_func('Controller\\MainController::get_products');
    }
}
