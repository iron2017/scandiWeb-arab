<?php

namespace App;

use Exception;
use PDO;

abstract class Queries
{
    // Connection
    private $obj;
    private $conn;

    public function __construct()
    {
        $this->obj = new DbConnect();
        $this->conn = $this->obj->connect();
    }

    function get_all()
    {
        $get_all = "SELECT * FROM products ORDER BY id";
        try {
            $stmt = $this->conn->query($get_all);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return Validator::handle_exceptions($e);
        }

        $listProducts = [];
        foreach ($results as $result) {
            $class = 'Model\\' . $result["productType"];
            $product = new $class();
            $product->set_id($result["id"]);
            $product->set_sku($result["sku"]);
            $product->set_name($result["name"]);
            $product->set_price($result["price"]);
            $attributes = $this->fetch_attributes($result["sku"]);
            foreach ($attributes as $res) {
                $product->add_one_attribute($res["name"], $res["value"], $res["unit"]);
            }
            $listProducts[] = $product->get_properties();
        }
        return $listProducts;
    }

    function fetch_attributes($sku)
    {
        $get_all = "SELECT * FROM attributes WHERE sku_product = :sku ORDER BY name";
        $stmt = $this->conn->prepare($get_all);
        $stmt->bindParam(':sku', $sku, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    /*** START DATABASE INSERTS ***/
    function saveProduct($sku, $name, $price, $productType, $attributes)
    {
        
        $sql = "INSERT INTO products(sku, name, price, productType) VALUES(:sku, :name, :price, :productType)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':sku', $sku, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':productType', $productType, PDO::PARAM_STR);
          $stmt->execute();
        try {
            
            $this->save_attributes($sku, $attributes);
           return true;
         
        } catch (PDOException $e) {
            // Log or handle the exception
            Validator::handle_exceptions($e);
            return false;
        }
    }

    function save_attributes($sku, $current_attributes)
    {
        $sql = "INSERT INTO attributes(name, value, unit, sku_product) VALUES(:name, :value, :unit, :sku)";
        $stmt = $this->conn->prepare($sql);

        foreach ($current_attributes as $value) {
            $stmt->bindParam(':name', $value["name"], PDO::PARAM_STR);
            $stmt->bindParam(':value', $value["value"], PDO::PARAM_STR);
            $stmt->bindParam(':unit', $value["unit"], PDO::PARAM_STR);
            $stmt->bindParam(':sku', $sku, PDO::PARAM_STR);
            $stmt->execute();
        }

       
        return true;
    }
    /*** END DATABASE INSERTS ***/

    /*** START DELETE SECTION ***/
    function delete_mass($inputs)
    {
        $placeholders = implode(',', array_fill(0, count($inputs), '?'));

        $sql = "DELETE p, a FROM products p LEFT JOIN attributes a ON p.sku = a.sku_product WHERE p.id IN ($placeholders)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($inputs);
        return $stmt->rowCount() > 0;
    }

    function delete_one($sku)
    {
        $sql = "DELETE FROM products WHERE sku = :sku";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':sku', $sku, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    /*** END DELETE SECTION ***/

    function query($sql)
    {
        $stmt = $this->conn->prepare($sql);
        try {
            $result = $stmt->execute();
        } catch (Exception $e) {
            Validator::handle_exceptions($e);
            $result = false;
        }
        return $result;
    }
}
