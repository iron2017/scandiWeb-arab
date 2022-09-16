<?php

namespace Model;

use Model\Product;

class DVD extends Product
{
    public function __construct()
    {
        parent::__construct();
        $this->set_type('DVD');
        $this->set_unit('MB');
        $this->set_typeAttr(["size"]);
    }
}
