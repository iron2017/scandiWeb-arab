<?php

namespace App;
use Exception;
use Model\DVD;
use Model\Book;
use Model\Furniture;

class Controller {

    public function createProduct($inputs)
    {
        try {
        $types = ['DVD','Book','Furniture'];//we can call select type from products each time, having a fixed array of types is better
        if(!(!empty($inputs->productType)
            && is_string($inputs->productType)
            && in_array($inputs->productType,$types)))
            throw new Exception("type");
        if(empty($sku)){
            throw new Exception("sku");
        }
        if(empty($name)){
            throw new Exception("name");
        }
        if(!(!empty($price) && is_numeric($price))){
            throw new Exception("price");
        }
        
        $class = 'Model\\'.$inputs->productType;
        $product = new $class();
        $product->set_sku($inputs->sku);
        $product->set_name($inputs->name);
        $product->set_price($inputs->price);
        $product->set_type($inputs->productType);
        $product->add_attributes($inputs->description);
        $result = $product->saveProduct();
        
        echo json_encode($result);
        }
        catch(Exception $e) {
            $this->handle_errors($e);
        }
    }


}


?>