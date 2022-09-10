<?php

    class DVD extends Product
    {
        public function __construct()
        {
            parent::__construct();
            $this->set_type('DVD');
            $this->set_unit('MB');
            $this->set_typeAttr(["size"]);
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