<?php

/*******************************
 * Ce fichier est la page d'accueil
 * 
 * Dans cette page, on affiche les CD disponibles
 * On peut aussi rechercher un CD en particulier par :
 * - Auteur
 * - Genre
 * - Prix
 ********************************/


session_start();

// Suppression des variables de session si on revient sur la page d'accueil après avoir validé le la commande
if (isset($_GET['retour'])) {
    unset($_SESSION['nom']);
    unset($_SESSION['prenom']);
    unset($_SESSION['adresse']);
    unset($_SESSION['ville']);
    unset($_SESSION['codePostal']);
    unset($_SESSION['pays']);
    unset($_SESSION['email']);
    unset($_SESSION['telephone']);
}

?>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet/less" type="text/css" href="../SCSS/index.scss" />
    <link rel="stylesheet/less" type="text/css" href="../SCSS/nav.scss" />
    <link rel="stylesheet/less" type="text/css" href="../SCSS/contact.scss" />
    <link rel="stylesheet/less" type="text/css" href="../SCSS/pallette.scss" />
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
    <title>Accueil</title>
</head>


<body>

    <?php include("nav.php"); ?>



    <main>
        <!-- Liste des CD -->
        <article class="shop-grid">
            <?php

            // On récupère les CD de la base de données
            // On récupère les CD qui correspondent à la recherche
            if (isset($_GET['titre']) && isset($_GET['genre']) && isset($_GET['min']) && isset($_GET['max'])) {
                // Si on a choisi le genre "Tous"
                if ($_GET['genre'] == 'Tous') {
                    $req = $db->prepare("SELECT * FROM CD where prix >= :min and prix <= :max and auteur like :titre");
                    $req->execute(array(
                        'titre' => '%' . $_GET['titre'] . '%',
                        'min' => $_GET['min'],
                        'max' => $_GET['max']
                    ));
                } 
                // Si on a choisi un genre en particulier
                else {
                    $req = $db->prepare("SELECT * FROM CD where prix >= :min and prix <= :max and genre == :genre and auteur like :titre");
                    $req->execute(array(
                        'titre' => '%' . $_GET['titre'] . '%',
                        'min' => $_GET['min'],
                        'max' => $_GET['max'],
                        'genre' => strval($_GET['genre'])
                    ));
                }
            } 
            // Si on a choisi le genre "Tous"
            elseif (isset($_GET['titre'])) {
                $req = $db->prepare("SELECT * FROM CD where titre like :titre");
                $req->execute(array(
                    'titre' => '%' . $_GET['titre'] . '%'
                ));
            } 
            // Si on a rien filtré
            else {
                $req = $db->prepare("SELECT * FROM CD");
            }
            // On execute la requête
            $req->execute();

            // On affiche les CD
            while ($row = $req->fetch()) {
                echo "<section class='shop-item' onclick='window.location.href=\"produit.php?id=" . $row['id'] . "\"'>";
                echo "<img src='afficheImage.php?id=" . $row['id'] . "' /> ";
                echo "<div class='shop-item-details'>";
                echo "<h3>" . $row['titre'] . "</h3>";
                echo "<p>" . $row['auteur'] . "</p>";
                echo "</div>";
                echo "</section>";
            }


            $db = null;
            ?>
        </article>

        <!-- Filtre -->
        <article>
            <section class="filtre">
                <h2>Filtre</h2>
                <form>
                    <!-- POUR CHAQUE LABEL ON VERIFIE SI ON A DEJA REMPLI LE CHAMP -->
                    <label for="auteur">Auteur</label>
                    <input type="text" name="titre" id="titre" <?php if (isset($_GET['titre'])) {
                                                                    echo "value='" . $_GET['titre'] . "'";
                                                                } ?>>


                    <label for="genre">Genre</label>
                    <select name="genre" id="genre">
                        <?php
                        include 'BD/BD.php';

                        $req = $db->prepare("SELECT * FROM genre");
                        $req->execute();
                        echo "<option value='Tous'>Tous</option>";
                        // On affiche tous les genres
                        while ($row = $req->fetch()) {
                            // Si on a déjà choisi un genre
                            if (isset($_GET['genre'])) {
                                // Si le genre est le même que celui choisi, on le sélectionne
                                if ($_GET['genre'] == $row['genre_name']) {
                                    echo "<option value='" . $row['genre_name'] . "' selected>" . $row['genre_name'] . "</option>";
                                } else {
                                    echo "<option value='" . $row['genre_name'] . "'>" . $row['genre_name'] . "</option>";
                                }
                            } 
                            // Si on n'a pas encore choisi de genre
                            else {
                                echo "<option value='" . $row['genre_name'] . "'>" . $row['genre_name'] . "</option>";
                            }
                        }
                        $db = null;
                        ?>
                    </select>


                    <label for="prix">Prix</label>
                    <div class="range">
                        <!-- On affiche le prix min et max sous forme de slider -->
                        <div class="range-slider">
                            <span class="range-selected"></span>
                        </div>
                        <div class="range-input">
                            <input type="range" class="min" min="0" max="200" <?php if (isset($_GET['min'])) {
                                                                                    echo "value='" . $_GET['min'] . "'";
                                                                                } else {
                                                                                    echo "value='0'";
                                                                                } ?>>
                            <input type="range" class="max" min="0" max="200" <?php if (isset($_GET['max'])) {
                                                                                    echo "value='" . $_GET['max'] . "'";
                                                                                } else {
                                                                                    echo "value='200'";
                                                                                } ?>>
                        </div>
                        <!-- On affiche le prix min et max sous forme de nombre -->
                        <div class="range-price">
                            <label for="min">Min</label>
                            <input type="number" name="min" <?php if (isset($_GET['min'])) {
                                                                echo "value='" . $_GET['min'] . "'";
                                                            } else {
                                                                echo "value='0'";
                                                            } ?>>
                            <label for="max">Max</label>
                            <input type="number" name="max" <?php if (isset($_GET['max'])) {
                                                                echo "value='" . $_GET['max'] . "'";
                                                            } else {
                                                                echo "value='200'";
                                                            } ?>>
                        </div>
                    </div>
                    <!-- Script pour le slider -->
                    <script>
                        // On récupère les valeurs min et max
                        let rangeMin = 10;
                        const range = document.querySelector(".range-selected");
                        const rangeInput = document.querySelectorAll(".range-input input");
                        const rangePrice = document.querySelectorAll(".range-price input");

                        rangeInput.forEach((input) => {
                            input.addEventListener("input", (e) => {
                                // On récupère la valeur min et max
                                let minRange = parseInt(rangeInput[0].value);
                                let maxRange = parseInt(rangeInput[1].value);
                                // Si la différence entre la valeur min et max est inférieure à 10, on ne peut pas les déplacer
                                if (maxRange - minRange < rangeMin) {
                                    // Si on veut déplacer la valeur min, on déplace la valeur max
                                    if (e.target.className === "min") {
                                        rangeInput[0].value = maxRange - rangeMin;
                                    } else {
                                        rangeInput[1].value = minRange + rangeMin;
                                    }
                                } 
                                // Sinon on peut les déplacer
                                else {
                                    rangePrice[0].value = minRange;
                                    rangePrice[1].value = maxRange;
                                    range.style.left = (minRange / rangeInput[0].max) * 100 + "%";
                                    range.style.right = 100 - (maxRange / rangeInput[1].max) * 100 + "%";
                                }
                            });
                        });

                        // On récupère les valeurs min et max
                        rangePrice.forEach((input) => {
                            input.addEventListener("input", (e) => {
                                // On récupère la valeur min et max
                                let minPrice = rangePrice[0].value;
                                let maxPrice = rangePrice[1].value;
                                // Si la différence entre la valeur min et max est inférieure à 10, on ne peut pas les déplacer
                                if (maxPrice - minPrice >= rangeMin && maxPrice <= rangeInput[1].max) {
                                    // On déplace la barre de sélection
                                    if (e.target.className === "min") {
                                        rangeInput[0].value = minPrice;
                                        range.style.left = (minPrice / rangeInput[0].max) * 100 + "%";
                                    } 
                                    // On déplace la barre de sélection
                                    else {
                                        rangeInput[1].value = maxPrice;
                                        range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
                                    }
                                }
                            });
                        });

                        // Lorsqu'on charge la page, on met à jour la barre de sélection
                        let minRange = parseInt(rangeInput[0].value);
                        let maxRange = parseInt(rangeInput[1].value);
                        range.style.left = (minRange / rangeInput[0].max) * 100 + "%";
                        range.style.right = 100 - (maxRange / rangeInput[1].max) * 100 + "%";

                    </script>
                    <input type="submit" value="Filtrer">
                </form>

                
                <?php
                // On vérifie que les variables existent
                if (isset($_GET['submit']) && $_GET['submit'] == 'Filtrer' && isset($_GET['titre']) && isset($_GET['genre']) && isset($_GET['min']) && isset($_GET['max'])) {
                    header("Location: index.php?titre=" . $_GET['titre'] . "&genre=" . $_GET['genre'] . "&min=" . $_GET['min'] . "&max=" . $_GET['max']);
                }

                ?>

            </section>

        </article>

    </main>
</body>

</html>