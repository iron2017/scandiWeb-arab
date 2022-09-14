<?php

    namespace App;
    use Exception;
    use Model\DVD;
    use Model\Book;
    use Model\Furniture;
    use Database\DbConnect;

    class MainClass
    {
        private $objDB;
        private $conn;

        public function __construct()
        {
            $this->objDB = new DbConnect();
            $this->conn = $this->objDB->connect();
        }

        public function createProduct($inputs)
        {
            try {
                $types = ['DVD','Book','Furniture'];//we can call select type from products each time, having a fixed array of types is better
                if(!(!empty($inputs->productType)
                    && is_string($inputs->productType)
                    && in_array($inputs->productType,$types)))
                    throw new Exception("type");
                
                $class = 'Model\\'.$inputs->productType;
                $product = new $class();
                $product->set_sku($inputs->sku);
                $product->set_name($inputs->name);
                $product->set_price($inputs->price);
                $product->set_type($inputs->productType);
                $product->add_attributes($inputs->description);
                
                $result = $this->saveProduct($product);
                echo json_encode($result);
            }
            catch(Exception $e) {
                $this->handle_errors($e);
            }
        }

        function saveProduct($product)
        {
            $sql = "INSERT INTO products(sku,name,price,type) VALUES(
                '".$product->get_sku()."',
                '".$product->get_name()."',
                ".$product->get_price().",
                '".$product->get_type()."'
                )";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute();
            if($result) {
                try{
                    $result = $this->save_attributes($product);
                } catch(Exception $e){
                    //delete row if the attributes aren't added
                    $sql = "DELETE FROM products WHERE sku = '".$product->get_sku()."'";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute();
                    $this->handle_errors($e);
                }
            }
            return $result;
        }

        public function getProductsAsObjects()
        {
            try {
                $listProducts = array();
                $results = $this->get_all();
                
                foreach($results as $result) {
                    $class = 'Model\\'.$result["type"];
                    $product = new $class();
                    $product->set_id($result["id"]);
                    $product->set_sku($result["sku"]);
                    $product->set_name($result["name"]);
                    $product->set_price($result["price"]);
                    $this->fetch_attributes($product);
                    array_push($listProducts, clone $product);
                }
                return $listProducts;
            } catch(Exception $e) {
                $this->handle_errors($e);
            }
        }

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

        function save_attributes($product)
        {
            $attributes = $product->get_attributes();
            if(empty($attributes) || !$product->verify_attributes())
                throw new Exception("attributes");
            $sql = "INSERT INTO attributes(name,value,unit,sku_product) VALUES";
            $sku = $product->get_sku();
            foreach($attributes as $key => $value) {
                $sql = $sql."(
                    '".$value["name"]."',
                    '".$value["value"]."',
                    '".$value["unit"]."',
                    '".$sku."'
                ),";
            }
            $sql = substr_replace($sql,"", -1);
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute();
            return $result;
        }

        public function fetch_attributes($product)
        {
            $get_all = "Select * from attributes where sku_product = '".$product->get_sku()."' order by name";
            $stmt = $this->conn->query($get_all);
            $results = $stmt->fetchAll();
            foreach($results as $res) {
                $product->add_attribute($res["name"], $res["value"], $res["unit"]);
            }
        }

                    /******************** SAVE GETALL DELETE ********************/

        public function get_All()
        {
            $get_all = "Select * from products order by id";
            $stmt = $this->conn->query($get_all);
            return $stmt->fetchAll();
        }

        public function delete_Mass($inputs)
        {
            try {
                if(empty($inputs)){
                    throw new Exception("checks");
                }
                $sql = "DELETE p, a FROM products p JOIN attributes a ON p.sku = a.sku_product Where p.id in (".implode(',', $inputs).")";
                $stmt = $this->conn->prepare($sql);
                $result = $stmt->execute();
                echo $result;
            }
            catch(Exception $e) {
                $this->handle_errors($e);
            }
        }
        
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
    }
?>