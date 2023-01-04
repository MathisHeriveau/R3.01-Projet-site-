<?php
    // Ouverture de la BD
    include 'BD/BD.php';

    // Récupération de l'id de l'image

    // Récupération de l'image
    if(isset($_GET['login'])){
        $req = $db->prepare("SELECT image FROM users WHERE id = :id");
        $req->execute(array('id' => $_GET['login']));
    }else{
        $id = $_GET['id'];
        $req = $db->prepare("SELECT image FROM CD WHERE id = :id");
        $req->execute(array('id' => $id));
    }
    $row = $req->fetch();

    // Affichage de l'image
    header("Content-type: image/jpeg");
    echo $row['image'];

    $db = null;
?>

