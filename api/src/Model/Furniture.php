<?php

    namespace Model;
    use Model\Product;
    use Exception;
    
    class Furniture extends Product
    {
        public function __construct()
        {
            parent::__construct();
            $this->set_type('Furniture');
            $this->set_unit('CM');
            $this->set_typeAttr(["height","width","length"]);
        }

        function get_description()
        {
            //i made sure to order by name when we get attributes
            //which means first row is height, second is length, third is width, so i just show them accordingly
            $arr = $this->get_attributes();
            if(empty($arr))
                throw new Exception("attributes");
            return "Dimensions : ".$arr[0]["value"]."x".$arr[2]["value"]."x".$arr[1]["value"];
        }
    }
?>