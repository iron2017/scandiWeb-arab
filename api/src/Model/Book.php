<?php

namespace Model;

use Model\Product;

class Book extends Product
{
    public function __construct()
    {
        parent::__construct();
        $this->set_type('Book');
        $this->set_unit('KG');
        $this->set_typeAttr(["weight"]);
    }
}
