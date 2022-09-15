<?php

    namespace Model;
    use Exception;
    use Database\DbConnect;

    abstract class Product
    {
        //connection
        private $obj;
        private $conn;
        
        //product properties
        private $id;
        private $sku;
        private $name;
        private $price;
        private $type;
        private $unit;
        private $attributes;
        private $typeAttr;

        public function __construct()
        {
            $this->obj = new DbConnect();
            $this->conn = $this->obj->connect();
            $this->attributes = array();
        }
        
        abstract protected function get_description();

/*** START PROPERTIES SETTERS AND GETTERS ***/
        function set_id($id)
        {
            $this->id = $id;
        }
        function get_id()
        {
            return $this->id;
        }

        function set_sku($sku)
        {
            $this->sku = $sku;
        }
        function get_sku()
        {
            return $this->sku;
        }

        function set_name($name)
        {
            $this->name = $name;
        }
        function get_name()
        {
            return $this->name;
        }
        
        function set_price($price)
        {
            $this->price = $price;
        }
        function get_price()
        {
            return $this->price;
        }
          
        function set_type($type)
        {
            $this->type = $type;
        }
        function get_type()
        {
            return $this->type;
        }
          
        function set_unit($unit)
        {
            $this->unit = $unit;
        }
        function get_unit()
        {
            return $this->unit;
        }
          
        function set_typeAttr($typeAttr)
        {
            $this->typeAttr = $typeAttr;
        }
        function get_typeAttr()
        {
            return $this->typeAttr;
        }

        function set_attributes($attributes)
        {
            $this->attributes = $attributes;
        }
        public function get_attributes()
        {
            return $this->attributes;
        }
        function add_one_attribute($name,$value,$unit)
        {
            array_push($this->attributes,["name"=>$name, "value"=>$value, "unit"=>$unit]);
        }

/*** END PROPERTIES SETTERS AND GETTERS ***/

/*** START DATABASE INSERTS ***/

        function saveProduct()
        {
            $sql = "INSERT INTO products(sku,name,price,type) VALUES(
                '".$this->get_sku()."',
                '".$this->get_name()."',
                ".$this->get_price().",
                '".$this->get_type()."'
                )";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute();
            if($result) {
                try{
                    $result = $this->save_attributes($this);
                } catch(Exception $e){
                    //delete row if the attributes weren't added
                    $sql = "DELETE FROM products WHERE sku = '".$this->get_sku()."'";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute();
                    throw $e;
                }
            }
            return $result;
        }

        function save_attributes()
        {
            $current_attributes = $this->get_attributes();
            $sql = "INSERT INTO attributes(name,value,unit,sku_product) VALUES";
            $sku = $this->get_sku();
            foreach($current_attributes as $key => $value) {
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

/*** END DATABASE INSERTS ***/

/*** START FETCH SECTION ***/

        public function get_All()
        {
            $get_all = "Select * from products order by id";
            $stmt = $this->conn->query($get_all);
            return $stmt->fetchAll();
        }

        public function fetch_attributes()
        {
            $get_all = "Select * from attributes where sku_product = '".$this->get_sku()."' order by name";
            $stmt = $this->conn->query($get_all);
            $results = $stmt->fetchAll();
            foreach($results as $res) {
                $this->add_one_attribute($res["name"], $res["value"], $res["unit"]);
            }
        }

/*** START FETCH SECTION ***/

/*** START DELETE SECTION ***/
        public function delete_mass($inputs)
        {
            $sql = "DELETE p, a FROM products p JOIN attributes a ON p.sku = a.sku_product Where p.id in (".implode(',', $inputs).")";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute();
            return $result;
        }

/*** END DELETE SECTION ***/

    }
?>