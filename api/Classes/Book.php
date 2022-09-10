<?php

    class Book extends Product
    {
        public function __construct()
        {
            parent::__construct();
            $this->set_type('Book');
            $this->set_unit('KG');
            $this->set_typeAttr(["weight"]);
        }

        function get_description()
        {
            $attr = $this->get_attributes();
            if(empty($attr))
                throw new Exception("attributes");
            $arr = $attr[0];
            return ucfirst($arr["name"])." : ".$arr["value"]." ".$arr["unit"];
        }
    }
?>