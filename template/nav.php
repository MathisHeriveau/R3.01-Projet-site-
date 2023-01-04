<?php
    // On redirige vers la page d'accueil
    include "../PhP/connexion/estConnecte.php";
    if(estConnecte()){ $connecte = true; }
    else{ $connecte = false; }

    echo "<nav>";
    echo "<img src='../Images/Logo.png' alt='logo' class='logo'>";
    echo "<ul>";
    echo "<li><a href='index.php'>Shop</a></li>";
    echo "<li><a href='#'>About</a></li>";
    echo "<li><a href='#'>FAQ</a></li>";
    echo "<li><a href='#'>Contact</a></li>";
    if($connecte){
        echo "<li><a href='../PhP/mesCD.php'>Mes CD</a></li>";
    }
    echo "</ul>";
    echo "<article>";
    echo "<section class='barreRecherche'>";
    echo "<form action='index.php' method='get'>";
    echo "<input type='text' name='titre' placeholder='Rechercher un CD'>";
    echo "</form>";
    echo "</section>";

    // Connexion à la base de données
    include '../PhP/BD/BD.php';
    if(isset($_SESSION['id'])){

        $req = $db->prepare("Select image from users where id = :id");
        $req->execute(array(
            'id' => $_SESSION['id']
        ));
        $req->execute();
        $row = $req->fetch();

        $image = $row['image'];
    }else{
        $image= null;
    }

    echo "<section class='profil'>";
    if($connecte){
        echo "<div class='monPanier'>";
        echo "<img src='../Images/image-removebg-preview%20(47).png' alt='panier' class='panier' onclick='window.location.href=\"panier.php\"'/>";
        $req = $db->prepare("Select count(*) from panier where idUser = :id");
        $req->execute(array(
            'id' => $_SESSION['id']
        ));
        $req->execute();
        $row = $req->fetch();
        echo "<p class='nbArticle'>" . $row['count(*)'] . "</p>";

        echo "</div>";

        echo "<a id='imageProfil' href='profil.php'>";
        if($image == null){
                            
            echo '<img src="../Images/Avatar.png" alt="profil">';
        }else{
            
            $id = $_SESSION['id'];
            echo "<img src=\"afficheImage.php?login=" . $id . "\">";
        }
        echo "</a>";

        echo "<a href='../PhP/connexion/deconnexion.php'>Déconnexion</a>";
    }
    else{
        echo "<img src='../Images/Avatar.png' alt='profil' class='profil'>";
        echo "<a href='../PhP/connexion/connexion.php'>Connexion</a>";
    }
    echo "</section>";

    echo "</article>";

    echo "</nav>";

?>
