<?php
    $url = $_SERVER['HTTP_HOST'];

    if ($url === "localhost"){
        define('URL','http://localhost/logistica/');
        define('PASSWORD','zBELTUAKpNQvCOl6');
    }else {
        define('URL','http://200.41.86.61:3000/logistica/');
        define('PASSWORD','odigo72');
    }
        
    //define('URL','http://192.168.0.3/logistica/');
    //define('URL','http://200.41.86.61:3000/logistica/');

    define('HOST','localhost');
    define('DB','logistica');
    define('DB2','rrhh');
    define('USER','root');
    //define('PASSWORD','zBELTUAKpNQvCOl6');
    //define('PASSWORD','odigo72');
    define('CHARSET','utf8mb4'); 


    define('VERSION',rand(0, 15000));
?>