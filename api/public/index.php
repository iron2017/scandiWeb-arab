<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once __DIR__ . '/../vendor/autoload.php';
include '../config.php';

use App\Router;

$router = new Router();

$router->get('', 'Controller\MainController::get_products');

$router->post('/api/addproduct.php', 'Controller\MainController::create_product');
$router->post('/api/deleteproducts.php', 'Controller\MainController::delete_products');

$router->check();
