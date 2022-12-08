<?php

    // Path: Php\TD8&9\PhP\BD\BD.php
    // Get the general path to the database file
    $path = dirname(__DIR__, 2) . "/BD";

    //$db = new PDO('sqlite:' . $path);
    $db = new PDO("mysql:host=lakartxela;dbname=mheriveau_bd", "mheriveau_bd", "mheriveau_bd");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);