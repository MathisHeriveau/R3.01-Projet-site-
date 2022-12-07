<?php
    // Ouverture de la BD
    $FICHIER_BD = "../../BD";
    $db = new PDO('sqlite:' . $FICHIER_BD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération de l'id de l'image
    $id = $_GET['id'];

    // Récupération de l'image
    $req = $db->prepare("SELECT image FROM CD WHERE id = :id");
    $req->execute(array('id' => $id));
    $row = $req->fetch();

    // Affichage de l'image
    header("Content-type: image/jpeg");
    echo $row['image'];

    $db = null;
?>

