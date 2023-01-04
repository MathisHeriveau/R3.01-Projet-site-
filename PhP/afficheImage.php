<?php

/*******************************
 * Ce fichier permet d'afficher une image
 * 
 * Il peut s'agir d'une image de profil ou d'une image de CD
 * Si le paramètre login est présent, alors c'est une image de profil
 * Sinon, c'est une image de CD
 * 
 * Il se déroule de la manière suivante:
 * - On récupère l'id de l'image
 * - On récupère l'image
 * - On affiche l'image
 ********************************/

// Ouverture de la BD
include 'BD/BD.php';

// Récupération de l'id de l'image

// Récupération de l'image
if (isset($_GET['login'])) {
    $req = $db->prepare("SELECT image FROM users WHERE id = :id");
    $req->execute(array('id' => $_GET['login']));
} else {
    $id = $_GET['id'];
    $req = $db->prepare("SELECT image FROM CD WHERE id = :id");
    $req->execute(array('id' => $id));
}
$row = $req->fetch();

// Affichage de l'image
header("Content-type: image/jpeg");
echo $row['image'];

$db = null;
