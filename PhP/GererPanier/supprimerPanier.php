<?php

/*******************************
 * Ce fichier permet de supprimer un CD du panier
 * 
 * Il se déroule de la manière suivante:
 * - On récupère l'id du CD et l'id de l'utilisateur
 * - On supprime le CD du panier
 * - On redirige vers la page panier.php
 ********************************/

// On récupère l'id du CD et l'id de l'utilisateur
if (isset($_GET['idUser']) && isset($_GET['idProduit'])) {

    $idProduit = $_GET['idProduit'];
    $idUser = $_GET['idUser'];


    include '../BD/BD.php';

    // On supprime le CD du panier
    $query = "DELETE FROM panier WHERE idUser = $idUser AND idCD = $idProduit";
    $db->exec($query);


    $db = null;
    header("Location: ../panier.php");
}
