<?php

    header("Access-Control-Allow-Origin: *" );
    header("Access-Control-Allow-Headers: *" );

    include "Classes/MainClass.php";

    MainClass::deleteProducts(json_decode(file_get_contents('php://input')));
?>