<?php

    namespace Model;
    use Exception;

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
        
        public function get_attributes()
        {
            return $this->attributes;
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
    }
?>