<?php

namespace App;
use Exception;
use Model\DVD;
use Model\Book;
use Model\Furniture;

class Controller {

/*** START CREATE NEW PRODUCT ***/

    public function createProduct($inputs)
    {
        try {
            $types = ['DVD','Book','Furniture'];//we can call select type from products each time, having a fixed array of types is better
            if(!(!empty($inputs->productType) && is_string($inputs->productType) && in_array($inputs->productType,$types)))
                throw new Exception("type");
            if(empty($inputs->sku)){
                throw new Exception("sku");
            }
            if(empty($inputs->name)){
                throw new Exception("name");
            }
            if(!(!empty($inputs->price) && is_numeric($inputs->price))){
                throw new Exception("price");
            }
            if(empty($inputs->description)){
                throw new Exception("description");
            }
            
            $class = 'Model\\'.$inputs->productType;
            $product = new $class();
            $product->set_sku($inputs->sku);
            $product->set_name($inputs->name);
            $product->set_price($inputs->price);
            $product->set_type($inputs->productType);
            $this->add_multiple_attributes($product, $inputs->description);
            if(!$this->verify_product_attributes($product)){
                throw new Exception("attributes");
            }
            $result = $product->saveProduct();
            
            echo json_encode($result);
        }
        catch(Exception $e) {
            $this->handle_errors($e);
        }
    }

    function add_multiple_attributes($product, $attributes)
    {
        foreach($attributes as $key => $value) {
            $unit = is_string($product->get_unit()) ? $product->get_unit() : null;
            if(empty($key))
                throw new Exception("attribute_name");
            if(empty($value))
                throw new Exception("attribute_value");
            $product->add_one_attribute($key,$value,$unit);
        }
    }

    public function verify_product_attributes($product)
    {
        $attribs = $product->get_attributes();
        $types = $product->get_typeAttr();

        foreach($types as $tAttr){
            $one = false;
            foreach($attribs as $attrib){
                if($tAttr == $attrib["name"]) $one = true;}
            if(!$one) return false;
        }
        return true;
    }

/*** END CREATE NEW PRODUCT ***/

/*** START FETCHING PRODUCTS ***/

    //this may not be the optimal solution for fetching products,
    //but i used it to show the usage of getters and setters
    public function getProductsAsJson()
    {
        try {
            $products = $this->getProductsAsObjects();
            $results = array();

            foreach($products as $prod){
                $row = ["id"=>$prod->get_id(),
                "sku"=>$prod->get_sku(),
                "name"=>$prod->get_name(),
                "price"=>$prod->get_price(),
                "type"=>$prod->get_type(),
                "description"=>$prod->get_description()
                ];
                array_push($results,$row);
            }
            echo json_encode($results);
        }
        catch(Exception $e) {
            $this->handle_errors($e);
        }
    }

    public function getProductsAsObjects()
    {
        try {
            $class = new DVD();
            $listProducts = array();
            $results = $class->get_all();
            
            foreach($results as $result) {
                $class = 'Model\\'.$result["type"];
                $product = new $class();
                $product->set_id($result["id"]);
                $product->set_sku($result["sku"]);
                $product->set_name($result["name"]);
                $product->set_price($result["price"]);
                $product->fetch_attributes();
                array_push($listProducts, clone $product);
            }
            return $listProducts;
        } catch(Exception $e) {
            $this->handle_errors($e);
        }
    }

/*** END FETCHING PRODUCTS ***/

/*** START DELETE PRODUCTS ***/

    public function delete_products($inputs)
    {
        try {
            if(empty($inputs)){
                throw new Exception("checks");
            }
            $class = new DVD();
            $results = $class->delete_mass($inputs);
            echo $results;
        }
        catch(Exception $e) {
            $this->handle_errors($e);
        }
    }

/*** END DELETE PRODUCTS ***/

/*** START ERROR HANDLING ***/
        
    public function handle_errors($e) {
        //empty fields possible errors
        $array = ["sku","name","price","type","size","weight","dimensions","attributes"];
        if($e->getCode() == 23000) {
            echo json_encode(["type"=>"unique", "field"=>"sku"]);
        }
        elseif($e->getMessage() == "checks"){
            echo "You didn't check any product";
        }
        elseif(in_array($e->getMessage(),$array)) {
            echo json_encode(["type"=>"empty", "field"=>$e->getMessage()]);
        }
        else {
            echo "Something went wrong with the server";
        }
    }

/*** END ERROR HANDLING ***/
}
?>