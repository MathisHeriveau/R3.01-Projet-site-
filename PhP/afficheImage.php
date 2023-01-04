<?php
    // Ouverture de la BD
    include 'BD/BD.php';

    // Récupération de l'id de l'image
    $id = $_GET['id'];

    // Récupération de l'image
    if(isset($_GET['photoProfil'])){
        $req = $db->prepare("SELECT image FROM users WHERE id = :id");
    }else{
        $req = $db->prepare("SELECT image FROM CD WHERE id = :id");

    }
    $req->execute(array('id' => $id));
    $row = $req->fetch();

    // Affichage de l'image
    header("Content-type: image/jpeg");
    echo $row['image'];

    $db = null;
?>

