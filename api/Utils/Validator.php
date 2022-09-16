<?php

namespace App;

class Validator
{
    public static function validate_inputs($inputs)
    {
        $error = array();

        if (empty($inputs))
            array_push($error, "no inputs were sent");
        else {
            $types = ['DVD', 'Book', 'Furniture']; //we can call select type from products each time, having a fixed array of types is better
            if (!(!empty($inputs->productType) && is_string($inputs->productType) && in_array($inputs->productType, $types)))
                array_push($error, "type is empty or doesn't exist");
            if (empty($inputs->sku)) {
                array_push($error, "sku is empty");
            }
            if (empty($inputs->name)) {
                array_push($error, "name is empty");
            }
            if (!(!empty($inputs->price) && is_numeric($inputs->price) && $inputs->price > 0)) {
                array_push($error, "price is empty or is not a valid number");
            }
            if (empty($inputs->description)) {
                array_push($error, "description is empty");
            }
        }

        return Validator::print_errors($error);
    }

    //this function checks whether the attributes we are added match the specefic type attributes
    public static function verify_product_attributes($product)
    {
        $attribs = $product->get_attributes();
        $types = $product->get_typeAttr();

        foreach ($types as $tAttr) {
            $one = false;
            foreach ($attribs as $attrib) {
                if ($tAttr == $attrib["name"]) $one = true;
            }
            if (!$one) return Validator::print_errors(["missing attributes"]);
        }
        return Validator::print_errors([]);
    }

    //prints the basic errors
    public static function print_errors($errors)
    {
        //empty fields possible errors
        $message = '';
        if (empty($errors)) return '';
        foreach ($errors as $key => $msg)
            $message = $message . " " . $msg . "\n";
        echo $message;
        return $message;
    }

    //prints responses to different database exceptions
    public static function handle_exceptions($e)
    {
        //empty fields possible exceptions
        if ($e->getCode() == 23000) {
            return ["type" => "unique", "field" => "sku"];
        } else {
            return ["else" => "Something went wrong with the server"];
        }
    }
}
