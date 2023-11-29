<?php

namespace Controller;

use App\Validator;
use Exception;
use Model\Product;

class MainController
{
    public static function get_products()
    {
        echo json_encode((new Product())->get_all());
    }

    public static function create_product($inputs)
    {
        $message = Validator::validate_inputs($inputs);
        if (!empty($message)) return $message;

        $class = 'Model\\' . $inputs->productType;
        $products = new $class();
        $products->set_properties($inputs);

        $message = Validator::verify_product_attributes($products);
        if (!empty($message)) return $message;

        $result = $products->save();
        echo json_encode($result);
    }

    public static function delete_products($inputs)
    {
        if (empty($inputs)) {
            $message = Validator::print_errors(["no products were selected"]);
            return $message;
        }
        echo (new Product())->delete_mass($inputs);
    }
}
