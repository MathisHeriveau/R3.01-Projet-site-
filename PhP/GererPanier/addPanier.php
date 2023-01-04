<?php

/*******************************
 * Ce fichier permet d'ajouter un produit dans le panier
 * 
 * Il se déroule de la manière suivante:
 * - On récupère l'id de l'utilisateur, l'id du produit et la quantité
 * - On vérifie si l'utilisateur a déjà un panier
 * - Si l'utilisateur a déjà un panier, on vérifie si le produit est déjà dans le panier
 * - Si le produit est déjà dans le panier, on met à jour la quantité
 * - Si le produit n'est pas dans le panier, on l'ajoute
 * - Si l'utilisateur n'a pas de panier, on en crée un
 * - On redirige vers la page panier.php
 ********************************/
session_start();

// On récupère l'id de l'utilisateur, l'id du produit et la quantité
if (isset($_SESSION['id']) && isset($_GET['idProduit']) && isset($_GET['quantite'])) {

    // On stocke les données dans des variables
    $idProduit = $_GET['idProduit'];
    $idUser = $_SESSION['id'];
    $quantite = $_GET['quantite'];


    include '../BD/BD.php';

    // On vérifie si l'utilisateur a déjà un panier
    $req = $db->prepare("SELECT * FROM panier where idUser = :idUser");
    $req->execute(array(
        'idUser' => $idUser
    ));
    $resultat = $req->fetch();

    if ($resultat) {
        // Si l'utilisateur a déjà un panier, on vérifie si le produit est déjà dans le panier
        $req = $db->prepare("SELECT * FROM panier where idUser = :idUser and idCD = :idProduit");
        $req->execute(array(
            'idUser' => $idUser,
            'idProduit' => $idProduit
        ));
        $resultat = $req->fetch();

        if ($resultat) {
            // Si le produit est déjà dans le panier, on met à jour la quantité
            $req = $db->prepare("UPDATE panier SET quantite = :quantite WHERE idUser = :idUser and idCD = :idProduit");
            $req->execute(array(
                'quantite' => $quantite,
                'idUser' => $idUser,
                'idProduit' => $idProduit
            ));
        } else {
            // Si le produit n'est pas dans le panier, on l'ajoute
            $req = $db->prepare("INSERT INTO panier (idUser, idCD, quantite) VALUES (:idUser, :idProduit, :quantite)");
            $req->execute(array(
                'idUser' => $idUser,
                'idProduit' => $idProduit,
                'quantite' => $quantite
            ));
        }
    } else {
        // Si l'utilisateur n'a pas de panier, on en crée un
        $req = $db->prepare("INSERT INTO panier (idUser, idCD, quantite) VALUES (:idUser, :idProduit, :quantite)");
        $req->execute(array(
            'idUser' => $idUser,
            'idProduit' => $idProduit,
            'quantite' => $quantite
        ));
    }
    // On ferme la connexion à la base de données
    $db = null;

    // On redirige vers la page panier.php
    header("Location: ../panier.php");

} elseif (!isset($_SESSION['id'])) {
    // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
    header("Location: ../connexion/connexion.php");
}
