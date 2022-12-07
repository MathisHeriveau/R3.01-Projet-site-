<?php
session_start();
if(isset($_SESSION['id']) && isset($_GET['idProduit']) && isset($_GET['quantite'])){
    $idProduit = $_GET['idProduit'];
    $idUser = $_SESSION['id'];
    $quantite = $_GET['quantite'];


    $FICHIER_BD = "../../BD";
    //$db = new PDO('sqlite:' . $FICHIER_BD);
    $db = new PDO("mysql:host=lakartxela;dbname=mheriveau_bd", "mheriveau_bd", "mheriveau_bd");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // On vérifie si l'utilisateur a déjà un panier
    $req = $db->prepare("SELECT * FROM panier where idUser = :idUser");
    $req->execute(array(
        'idUser' => $idUser
    ));
    $resultat = $req->fetch();
    if($resultat){
        // Si l'utilisateur a déjà un panier, on vérifie si le produit est déjà dans le panier
        $req = $db->prepare("SELECT * FROM panier where idUser = :idUser and idCD = :idProduit");
        $req->execute(array(
            'idUser' => $idUser,
            'idProduit' => $idProduit
        ));
        $resultat = $req->fetch();
        if($resultat){
            // Si le produit est déjà dans le panier, on met à jour la quantité
            $req = $db->prepare("UPDATE panier SET quantite = :quantite WHERE idUser = :idUser and idCD = :idProduit");
            $req->execute(array(
                'quantite' => $quantite,
                'idUser' => $idUser,
                'idProduit' => $idProduit
            ));
        }
        else{
            // Si le produit n'est pas dans le panier, on l'ajoute
            $req = $db->prepare("INSERT INTO panier (idUser, idCD, quantite) VALUES (:idUser, :idProduit, :quantite)");
            $req->execute(array(
                'idUser' => $idUser,
                'idProduit' => $idProduit,
                'quantite' => $quantite
            ));
        }
    }
    else{
        // Si l'utilisateur n'a pas de panier, on en crée un
        $req = $db->prepare("INSERT INTO panier (idUser, idCD, quantite) VALUES (:idUser, :idProduit, :quantite)");
        $req->execute(array(
            'idUser' => $idUser,
            'idProduit' => $idProduit,
            'quantite' => $quantite
        ));
    }
    $db = null;
    header("Location: ../panier.php");

}elseif(!isset($_SESSION['id'])){
    header("Location: ../connexion/connexion.php");
}