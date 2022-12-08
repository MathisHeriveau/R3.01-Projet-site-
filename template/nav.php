<?php
    // On redirige vers la page d'accueil
    include "../PhP/connexion/estConnecte.php";
    if(estConnecte()){ $connecte = true; }
    else{ $connecte = false; }

    echo "<nav>";
    echo "<img src='../Images/Logo.png' alt='logo' class='logo'>";
    echo "<ul>";
    echo "<li><a href='index.php'>Shop</a></li>";
    echo "<li><a href='about.php'>About</a></li>";
    echo "<li><a href='FAQ.php'>FAQ</a></li>";
    echo "<li><a href='contact.php'>Contact</a></li>";
    if($connecte){
        echo "<li><a href='../PhP/mesCD.php'>Mes CD</a></li>";
    }
    echo "</ul>";
    echo "<article>";
    echo "<section class='barreRecherche'>";
    echo "<input type='text' class='search' name='search' placeholder='Rechercher...'/>";
    echo "</section>";
    // Connexion à la base de données
    include '../PhP/BD/BD.php';

    // Requête pour récupérer les CD
    $req = $db->prepare("SELECT titre FROM CD");
    $req->execute();

    // Affichage les titres des 5 premiers CD
    

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

        echo "<img src='../Images/Avatar.png' alt='profil' class='profil'>";

        echo "<a href='../PhP/connexion/deconnexion.php'>Déconnexion</a>";
    }
    else{
        echo "<img src='../Images/Avatar.png' alt='profil' class='profil'>";
        echo "<a href='../PhP/connexion/connexion.php'>Connexion</a>";
    }
    echo "</section>";

    echo "</article>";

    echo "</nav>";

    // Connexion à la base de données
    include '../PhP/BD/BD.php';

    // Requête pour récupérer les CD
    $req = $db->prepare("SELECT titre FROM CD");
    $req->execute();

    // Affichage les titres tous les cd dans un ul li
    echo "<ul name='listeTitreRecherche' style='display: none'>";
    while ($row = $req->fetch()) {
        echo "<li>" . $row['titre'] . "</li>";
    }
    echo "</ul>";


?>
<script>
    // On récup
    var listeTitreRecherche = document.getElementsByName("listeTitreRecherche")[0];
    var search = document.getElementsByClassName("search")[0];
    var suggestions = document.getElementsByClassName("suggestions")[0];

    // On ajoute un évènement à la barre de recherche
    search.addEventListener("keyup", function(){
        // On récupère la valeur de la barre de recherche
        var valeur = search.value;
        // On récupère la liste des titres
        var listeTitre = listeTitreRecherche.getElementsByTagName("li");
        // On vide la liste des suggestions
        suggestions.innerHTML = "";

        // Si la valeur de la barre de recherche est vide, on ne fait rien
        if(valeur == ""){
            return;
        }
        else{
            // On parcourt la liste des titres
            for(var i = 0; i < listeTitre.length; i++){
                // Si le titre commence par la valeur de la barre de recherche
                if(listeTitre[i].innerHTML.startsWith(valeur)){
                    // On ajoute le titre à la liste des suggestions
                    suggestions.innerHTML += "<option class='suggestion'>" + listeTitre[i].innerHTML + "</option>";
                }
            }
        }

    });
</script>
