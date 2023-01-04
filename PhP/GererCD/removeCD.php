<?php

/*******************************
 * Ce fichier permet de supprimer un CD
 * 
 * Il se déroule de la manière suivante:
 * - On récupère l'id du CD
 * - On supprime le CD
 * - On redirige vers la page mesCD.php
 ********************************/

$id = $_GET['id'];

include '../BD/BD.php';

// Delete the CD
$db->exec("DELETE FROM CD WHERE id = $id");

header("Location: ../mesCD.php");
