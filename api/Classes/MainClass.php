<?php

    require "Product.php";
    require "DVD.php";
    require "Book.php";
    require "Furniture.php";

    class MainClass
    {
        public static function createProduct($inputs)
        {
            try {
                $types = ['DVD','Book','Furniture'];//we can call select type from products each time, having a fixed array of types is better
                if(!(!empty($inputs->productType)
                    && is_string($inputs->productType)
                    && in_array($inputs->productType,$types)))
                    throw new Exception("type");
                
                $product = new $inputs->productType();
                $product->set_sku($inputs->sku);
                $product->set_name($inputs->name);
                $product->set_price($inputs->price);
                $product->set_type($inputs->productType);
                $product->add_attributes($inputs->description);
                
                $result = $product->saveProduct();
                echo json_encode($result);
            }
            catch(Exception $e) {
                //empty fields possible errors
                $array = ["sku","name","price","type","size","weight","dimensions","attributes"];
                if($e->getCode() == 23000) {
                    echo json_encode(["type"=>"unique", "field"=>"sku"]);
                }
                elseif(in_array($e->getMessage(),$array)) {
                    echo json_encode(["type"=>"empty", "field"=>$e->getMessage()]);
                }
                else {
                    echo "Something went wrong with the server";
                }
            }
        }

        public static function deleteProducts($inputs)
        {
            try{
                $product = new DVD();
                $result = $product->delete_Mass($inputs);
                echo $result;
            }
            catch(Exception $e){
                echo $e;
                if($e->getMessage() == "checks")
                    echo "You didn't check any product";
                else
                    echo "Something went wrong with the server";
            }
        }

        public static function getProductsAsObjects()
        {
            try {
            $product = new DVD();
            $listProducts = array();
            $results = $product->get_all();
            foreach($results as $result) {
                $class = ''.$result["type"];
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
                echo "Something went wrong with the server";
            }
        }

        public static function getProductsAsJson()
        {
            try {
                $products = MainClass::getProductsAsObjects();
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
                echo "Something went wrong with the server";
            }
        }
    }
?>