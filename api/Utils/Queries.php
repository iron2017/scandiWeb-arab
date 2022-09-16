<?php

namespace App;

use Exception;

abstract class Queries
{
    //connection
    private $obj;
    private $conn;

    public function __construct()
    {
        $this->obj = new DbConnect();
        $this->conn = $this->obj->connect();
    }

    function get_all()
    {
        $get_all = "Select * from products order by id";
        $stmt = $this->conn->query($get_all);
        try {
            $results = $stmt->fetchAll();
        } catch (Exception $e) {
            return Validator::handle_exceptions($e);
        }

        $listProducts = array();
        foreach ($results as $result) {
            $class = 'Model\\' . $result["type"];
            $product = new $class();
            $product->set_id($result["id"]);
            $product->set_sku($result["sku"]);
            $product->set_name($result["name"]);
            $product->set_price($result["price"]);
            $attributes = $this->fetch_attributes($result["sku"]);
            foreach ($attributes as $res) {
                $product->add_one_attribute($res["name"], $res["value"], $res["unit"]);
            }
            array_push($listProducts, $product->get_properties());
        }
        return $listProducts;
    }

    function fetch_attributes($sku)
    {
        $get_all = "Select * from attributes where sku_product = '" . $sku . "' order by name";
        $stmt = $this->conn->query($get_all);
        $results = $stmt->fetchAll();
        return $results;
    }

    /*** START DATABASE INSERTS ***/
    function saveProduct($sku, $name, $price, $type, $attributes)
    {
        $sql = "INSERT INTO products(sku,name,price,type) VALUES(
                '" . $sku . "',
                '" . $name . "',
                " . $price . ",
                '" . $type . "'
                )";
        $result = $this->query($sql);
        if (!is_array($result))
            $result = $this->save_attributes($sku, $attributes);

        return $result;
    }

    function save_attributes($sku, $current_attributes)
    {
        $sql = "INSERT INTO attributes(name,value,unit,sku_product) VALUES";
        foreach ($current_attributes as $key => $value) {
            $sql = $sql . "(
                    '" . $value["name"] . "',
                    '" . $value["value"] . "',
                    '" . $value["unit"] . "',
                    '" . $sku . "'),";
        }
        $sql = substr_replace($sql, "", -1);
        $result = $this->query($sql);
        if (is_array($result))
            $result = $this->delete_one($sku);
        return $result;
    }
    /*** END DATABASE INSERTS ***/

    /*** START DELETE SECTION ***/
    function delete_mass($inputs)
    {
        $sql = "DELETE p, a FROM products p LEFT JOIN attributes a ON p.sku = a.sku_product Where p.id in (" . implode(',', $inputs) . ")";
        return $this->query($sql);
    }
    function delete_one($sku)
    {
        $sql = "DELETE FROM products WHERE sku = '" . $sku . "'";
        return $this->query($sql);
    }
    /*** END DELETE SECTION ***/

    function query($sql)
    {
        $stmt = $this->conn->prepare($sql);
        try {
            $result = $stmt->execute();
        } catch (Exception $e) {
            return Validator::handle_exceptions($e);
        }
        return $result;
    }
}
