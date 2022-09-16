<?php

namespace Model;

use App\Queries;

class Product extends Queries
{

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
        parent::__construct();
        $this->attributes = array();
    }

    function set_properties($inputs)
    {
        if (!empty($inputs->id))
            $this->id = $inputs->id;
        $this->sku = $inputs->sku;
        $this->name = $inputs->name;
        $this->price = $inputs->price;
        $this->type = $inputs->productType;
        $this->add_multiple_attributes($inputs->description);
    }
    function get_properties()
    {
        $row = [
            "id" => $this->id,
            "sku" => $this->sku,
            "name" => $this->name,
            "price" => $this->price,
            "type" => $this->type,
            "description" => $this->get_description()
        ];
        return $row;
    }
    function get_description()
    {
        $attr = $this->get_attributes();
        if (empty($attr))
            return "";
        $arr = $attr[0];
        return ucfirst($arr["name"]) . " : " . $arr["value"] . " " . $arr["unit"];
    }

    function add_one_attribute($name, $value, $unit)
    {
        array_push($this->attributes, ["name" => $name, "value" => $value, "unit" => $unit]);
    }
    function add_multiple_attributes($attributes)
    {
        foreach ($attributes as $key => $value) {
            $unit = is_string($this->unit) ? $this->unit : null;
            if (!empty($key) && !empty($value) && $value > 0)
                $this->add_one_attribute($key, $value, $unit);
        }
    }


    public function save()
    {
        return $this->saveProduct($this->get_sku(), $this->get_name(), $this->get_price(), $this->get_type(), $this->get_attributes());
    }




    /*** basic setters and getters ***/
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
    function get_attributes()
    {
        return $this->attributes;
    }
}
