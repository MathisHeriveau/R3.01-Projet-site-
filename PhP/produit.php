<?php

/*******************************
 * Ce fichier permet d'afficher un produit
 * 
 * Il se déroule de la manière suivante:
 * - On récupère l'id du produit
 * - On récupère les informations du produit
 * - On affiche les informations du produit
 * 
 * Il est possible de le mettre dans un panier
 * Il est possible de l'acheter (non implémenté)
 ********************************/

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
}
session_start();
?>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet/less" type="text/css" href="../SCSS/nav.scss" />
    <link rel="stylesheet/less" type="text/css" href="../SCSS/produit.scss" />
    <link rel="stylesheet/less" type="text/css" href="../SCSS/contact.scss" />
    <link rel="stylesheet/less" type="text/css" href="../SCSS/pallette.scss" />

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
    <title>Produit</title>
</head>

<body>
    <?php include("nav.php"); ?>
    <main>
        <section class="produit">
            <?php
            include 'BD/BD.php';

            $req = $db->prepare("SELECT * FROM CD WHERE id = :id");
            $req->bindParam(':id', $_GET['id']);
            $req->execute();

            $row = $req->fetch();
            echo "<section class='produit-image'>";
            echo "<img src='afficheImage.php?id=" . $row['id'] . "' /> ";
            echo "</section>";
            echo "<section class='produit-description'>";
            echo "<h1>" . $row['titre'] . "</h1>";
            echo "<h4>Ref: " . $row['id'] . "</h4>";
            echo "<label for='auteur'>Auteur: " . $row['auteur'] . "</label>";
            echo "<label for='genre'>Genre: " . $row['genre'] . "</label>";
            echo "<label id='prix' for='prix'>" . $row['prix'] . ".00€</label>";
            echo "<label for='quantite'>Quantité: </label>";
            echo "<input type='number' name='quantity' min='1' max =" . $row['quantite'] . " value='1'>";

            echo "<button type='submit' name='add' onclick='window.location.href = \"GererPanier/addPanier.php?idProduit=" . $row['id'] . "&quantite=\" + document.querySelector(\"input[name=quantity]\").value'>Ajouter au panier</button>";
            echo "<button type='submit' name='buy'>Acheter maintenant</button>";
            echo "<h3>Product information</h3>";
            echo "<p>" . $row['description'] . "</p>";
            echo "</section>";
            echo "</section>";
            ?>

        </section>

    </main>

</body>

</html>