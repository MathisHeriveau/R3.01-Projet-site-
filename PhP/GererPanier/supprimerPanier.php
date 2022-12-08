<?php
    if(isset($_GET['idUser']) && isset($_GET['idProduit'])){
        $idProduit = $_GET['idProduit'];
        $idUser = $_GET['idUser'];


        include '../BD/BD.php';

        $query = "DELETE FROM panier WHERE idUser = $idUser AND idCD = $idProduit";
        $db->exec($query);


        $db = null;
        header("Location: ../panier.php");
    }