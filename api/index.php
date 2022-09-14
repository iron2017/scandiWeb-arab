<?php

header("Access-Control-Allow-Origin: *" );
header("Access-Control-Allow-Headers: *" );

require_once __DIR__ . '/vendor/autoload.php';

use App\MainClass;

$controller = new MainClass();
$method = $_SERVER['REQUEST_METHOD'];
$path = explode('/', $_SERVER['REQUEST_URI']);

switch($method) {
    case "GET":
        if(isset($path[2]) && $path[2] == '' ) {
            $controller->getProductsAsJson();
        }
        break;
    case "POST":
        if(isset($path[2]) && $path[2] == 'addproduct.php' ) {
            $new_product = json_decode( file_get_contents('php://input') );
            $controller->createProduct($new_product);
        }
        elseif(isset($path[2]) && $path[2] == 'deleteproducts.php' ) {
            $id_list = json_decode( file_get_contents('php://input') );
            $controller->delete_Mass($id_list);
        }
        break;
}
?>