<?php

    // Localhost
    /*
    $path = dirname(__DIR__, 2) . "/BD";
    $db = new PDO('sqlite:' . $path);
    */

    // Serveur
    
    $config = parse_ini_file('BD.ini', true);
    $HOST = $config['HOST']['host'];
    $USER = $config['LOGIN']['USER'];
    $PASSWORD = $config['LOGIN']['PASSWORD'];
    $DATABASE = $config['DATABASE']['DATABASE'];
    $db = new PDO('mysql:host=' . $HOST . ';dbname=' . $DATABASE, $USER, $PASSWORD);
    


    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);