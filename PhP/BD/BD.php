<?php

/*******************************
 * Ce fichier permet de se connecter à la base de données
 * Il peut se faire de deux manières:
 * - En local
 *     => J'utilise un fichier .sqlite 
 * - Sur le serveur
 *      => J'utilise un fichier .ini pour stocker les informations de connexion
 ********************************/


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
