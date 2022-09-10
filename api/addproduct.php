<?php

    header("Access-Control-Allow-Origin: *" );
    header("Access-Control-Allow-Headers: *" );

    include "Classes/MainClass.php";

    MainClass::createProduct(json_decode(file_get_contents('php://input')));
?>