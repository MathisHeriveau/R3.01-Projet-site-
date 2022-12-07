<?php
    session_start();
    if(!isset($_SESSION['id'])){
        header('Location: connexion/connexion.php');
    }
?>


<html>
<head>
    <meta charset="utf-8">
    <title>Mon panier</title>
    <meta charset="utf-8">
    <link rel="stylesheet/less" type="text/css" href="../SCSS/nav.scss"/>
    <link rel="stylesheet/less" type="text/css" href="../SCSS/pallette.scss"/>
    <link rel="stylesheet/less" type="text/css" href="../SCSS/panier.scss"/>

    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
</head>

<body>
    <?php
        include '../template/nav.php';
    ?>

   <main>
       <section class="panier">
           <h2>Mon panier</h2>
           <div class="mesArticle">
       <?php

           $FICHIER_BD = "../BD";
           //$db = new PDO('sqlite:' . $FICHIER_BD);
           $db = new PDO("mysql:host=lakartxela;dbname=mheriveau_bd", "mheriveau_bd", "mheriveau_bd");
           $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $req = $db->prepare("SELECT * FROM panier where idUser = :id");
            $req->execute(array(
                'id' => $_SESSION['id']
            ));
            $req->execute();


            $cd = $db->prepare("SELECT * FROM CD where id = :id");

            // Si le panier est vide
            if ($req->rowCount() == 0) {
                echo "<h1>Votre panier est vide</h1>";
            } else {
                while ($row = $req->fetch()) {
                    echo "<div class='unArticle'>";
                    $cd->execute(array(
                        'id' => $row['idCD']
                    ));
                    $cd->execute();
                    $cdRow = $cd->fetch();
                    echo "<img src='afficheImage.php?id=" . $cdRow['id'] . "' />";
                    echo "<div class='info'>";
                    echo "<h3>Prix : " . $cdRow['prix'] . ".00€</h3>";
                    echo "<h4>Titre : " . $cdRow['titre'] . "</h4>";
                    echo "<h4>Auteur : " . $cdRow['auteur'] . "</h4>";
                    echo "<div class='quantite'>";
                    echo "<h4>Quantité</h4>";
                    echo "<input type='number' value='" . $row['quantite'] . "' min='1' max='10' />";
                    echo "</div>";
                    echo "</div>";
                    echo "<a href='GererPanier/supprimerPanier.php?idProduit=" . $cdRow['id'] . "&idUser=" . $_SESSION['id'] . "'>X</a>";
                    echo "</div>";
                }

            }


       ?> </div>
           <div class="sous-total">
               <h3>Sous-total</h3>
               <?php
               $req = $db->prepare("SELECT * FROM panier where idUser = :id");
               $req->execute(array(
                   'id' => $_SESSION['id']
               ));
               $req->execute();

               $cd = $db->prepare("SELECT * FROM CD where id = :id");

               $total = 0;
               while ($row = $req->fetch()) {
                   $cd->execute(array(
                       'id' => $row['idCD']
                   ));
                   $cd->execute();
                   $cdRow = $cd->fetch();
                   $total += $cdRow['prix'] * $row['quantite'];
               }
               echo "<h3>" . $total . ",00€</h3>";
               ?>
           </div>


       </section>

       <section class="paiement">
           <h2>Total</h2>
           <div class="sous-total">
               <h3>Sous-total</h3>
               <?php
               $req = $db->prepare("SELECT * FROM panier where idUser = :id");
               $req->execute(array(
                   'id' => $_SESSION['id']
               ));
               $req->execute();

               $cd = $db->prepare("SELECT * FROM CD where id = :id");

               $total = 0;
               while ($row = $req->fetch()) {
                   $cd->execute(array(
                       'id' => $row['idCD']
                   ));
                   $cd->execute();
                   $cdRow = $cd->fetch();
                   $total += $cdRow['prix'] * $row['quantite'];
               }
               echo "<h3>" . $total . ",00€</h3>";
               ?>
           </div>
            <div class="livraison">
                <h3>Livraison</h3>
                <select name="livraison" id="livraison">
                    <option value="gratuite">Livraison Standard en point relais (gratuite)</option>
                    <option value="express">Livraison Express à domicile (9,95€)</option>
                </select>
            </div>
           <button type="submit" name="btnPaiement" onclick="window.location.href='commander.php'">Paiement</button>

               <div class="typePaiment">
               <h3>Nous acceptons :</h3>
                <img src="../Images/typePaiement.png" alt="typePaiement">
               <p>Vous avez un code promotionnel ? Ajoutez-le a la prochaine étape.</p>

           </div>
         </section>
   </main>
    <div class="commander">
        <img src="../Images/livraison-gratuite.png" alt="livraison">
        <div class="livraison">
            <h3>LIVRAISON ASOS PREMIER FRANCE</h3>
            <p>Bénéficiez de la livraison 24h à domicile ou en point relais illimitée pendant un an pour seulement 9,95€</p>
        </div>
        <a href="#"> &#9660</a>
    </div>

</body>



</html>