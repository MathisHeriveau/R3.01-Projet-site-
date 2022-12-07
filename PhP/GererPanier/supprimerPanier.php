<?php
    if(isset($_GET['idUser']) && isset($_GET['idProduit'])){
        $idProduit = $_GET['idProduit'];
        $idUser = $_GET['idUser'];


        $FICHIER_BD = "../BD";
        //$db = new PDO('sqlite:' . $FICHIER_BD);
        $db = new PDO("mysql:host=lakartxela;dbname=mheriveau_bd", "mheriveau_bd", "mheriveau_bd");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "DELETE FROM panier WHERE idUser = $idUser AND idCD = $idProduit";
        $db->exec($query);


        $db = null;
        header("Location: ../panier.php");
    }