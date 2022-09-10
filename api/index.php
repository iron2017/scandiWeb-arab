<?php

    header("Access-Control-Allow-Origin: *" );
    header("Access-Control-Allow-Headers: *" );
    
    include "Classes/MainClass.php";
    
    MainClass::getProductsAsJson();
?>