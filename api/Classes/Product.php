<?php
    
    require "DbConnect.php";

    abstract class Product
    {
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
            $this->objDB = new DbConnect;
            $this->conn = $this->objDB->connect();
            $this->attributes = array();
        }

        
        abstract protected function get_description();

                    /******************** SETTERS AND GETTERS ********************/
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
            if(empty($sku)){
                throw new Exception("sku");
            }
            $this->sku = $sku;
        }
        function get_sku()
        {
            return $this->sku;
        }

        function set_name($name)
        {
            if(empty($name)){
                throw new Exception("name");
            }
            $this->name = $name;
        }
        function get_name()
        {
            return $this->name;
        }
        
        function set_price($price)
        {
            if(!(!empty($price) && is_numeric($price))){
                throw new Exception("price");
            }
            $this->price = $price;
        }
        function get_price()
        {
            return $this->price;
        }
          
        function set_type($type)
        {
            if(empty($type)){
                throw new Exception("type");
            }
            $this->type = $type;
        }
        function get_type()
        {
            return $this->type;
        }
          
        function set_unit($unit)
        {
            if(empty($unit)){
                throw new Exception("unit");
            }
            $this->unit = $unit;
        }
        function get_unit()
        {
            return $this->unit;
        }
          
        function set_typeAttr($typeAttr)
        {
            if(empty($typeAttr)){
                throw new Exception("typeAttr");
            }
            $this->typeAttr = $typeAttr;
        }
        function get_typeAttr()
        {
            return $this->typeAttr;
        }

        function verify_attributes()
        {
            $attribs = $this->get_attributes();
            $types = $this->get_typeAttr();

            foreach($types as $tAttr){
                $one = false;
                foreach($attribs as $attrib){
                    if($tAttr == $attrib["name"]) $one = true;}
                if(!$one) return false;
            }
            return true;
        }

                    /******************** ATTRIBUTES MANAGEMENT ********************/

        function add_attribute($name,$value,$unit)
        {
            if(empty($name))
                throw new Exception("attribute_name");
            if(empty($value))
                throw new Exception("attribute_value");
            array_push($this->attributes,["name"=>$name, "value"=>$value, "unit"=>$unit]);
        }
        function add_attributes($attrs)
        {
            if(empty($attrs))
                throw new Exception("attributes");
            foreach($attrs as $key => $value) {
                $unit = is_string($this->unit) ? $this->unit : null;
                $this->add_attribute($key,$value,$unit);
            }
        }
        function save_attributes()
        {
            if(empty($this->attributes) || !$this->verify_attributes())
                throw new Exception("attributes");
            $sql = "INSERT INTO attributes(name,value,unit,sku_product) VALUES";
            foreach($this->attributes as $key => $value) {
                $sql = $sql."(
                    '".$value["name"]."',
                    '".$value["value"]."',
                    '".$value["unit"]."',
                    '".$this->sku."'
                ),";
            }
            $sql = substr_replace($sql,"", -1);
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute();
            return $result;
        }
        public function fetch_attributes()
        {
            $get_all = "Select * from attributes where sku_product = '".$this->get_sku()."' order by name";
            $stmt = $this->conn->query($get_all);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($results as $res) {
                array_push($this->attributes,["name"=>$res["name"], "value"=>$res["value"], "unit"=>$res["unit"]]);
            }
        }
        public function get_attributes()
        {
            return $this->attributes;
        }

                    /******************** SAVE GETALL DELETE ********************/

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
                    $result = $this->save_attributes();
                } catch(Exception $e){
                    //delete row if the attributes aren't added
                    $sql = "DELETE FROM products WHERE sku = '".$this->get_sku()."'";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute();
                    throw $e;
                }
            }
            return $result;
        }

        public function get_All()
        {
            $get_all = "Select * from products order by id";
            $stmt = $this->conn->query($get_all);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function delete_Mass($inputs)
        {
            if(empty($inputs)){
                throw new Exception("checks");
            }
            $sql = "DELETE p, a FROM products p JOIN attributes a ON p.sku = a.sku_product Where p.id in (".implode(',', $inputs).")";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute();
            return $result;
        }
    }
?>