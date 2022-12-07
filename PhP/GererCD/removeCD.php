<?php

$id = $_GET['id'];

$FICHIER_BD = "../../BD";
//$db = new PDO('sqlite:' . $FICHIER_BD);
$db = new PDO("mysql:host=lakartxela;dbname=mheriveau_bd", "mheriveau_bd", "mheriveau_bd");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Delete the CD
$db->exec("DELETE FROM CD WHERE id = $id");

header("Location: ../mesCD.php");
