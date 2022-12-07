<?php session_start();
// Suppression des variables de session si on revient sur la page
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
    <link rel="stylesheet/less" type="text/css" href="../SCSS/index.scss"/>
    <link rel="stylesheet/less" type="text/css" href="../SCSS/nav.scss"/>
    <link rel="stylesheet/less" type="text/css" href="../SCSS/contact.scss"/>
    <link rel="stylesheet/less" type="text/css" href="../SCSS/pallette.scss"/>
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
    <title>Accueil</title>
</head>


<body>

    <?php include("../template/nav.php"); ?>



    <main>
        <article class="shop-grid">
            <?php
                $FICHIER_BD = "../../BD";
                $db = new PDO('sqlite:' . $FICHIER_BD);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //titre=An%27om&genre=Tous&min=50&max=150
                if(isset($_GET['titre']) && isset($_GET['genre']) && isset($_GET['min']) && isset($_GET['max'])){
                    $req = $db->prepare("SELECT * FROM CD where prix >= :min and prix <= :max and genre == :genre and auteur like :titre");
                    $req->execute(array(
                        'titre' => '%'.$_GET['titre'].'%',
                        'min' => $_GET['min'],
                        'max' => $_GET['max'],
                        'genre' => strval($_GET['genre'])
                    ));
                    if($_GET['genre']=='Tous'){
                        $req = $db->prepare("SELECT * FROM CD where prix >= :min and prix <= :max and auteur like :titre");
                        $req->execute(array(
                            'titre' => '%'.$_GET['titre'].'%',
                            'min' => $_GET['min'],
                            'max' => $_GET['max']
                        ));
                    }

                }else{
                    $req = $db->prepare("SELECT * FROM CD");
                }
                $req->execute();


            while ($row = $req->fetch()) {
                    echo "<section class='shop-item' onclick='window.location.href=\"produit.php?id=".$row['id']."\"'>";
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
        <article>
            <section class="filtre">
                <h2>Filtre</h2>
                <form>
                    <label for="auteur">Auteur</label>
                    <input type="text" name="titre" id="titre" <?php if(isset($_GET['titre'])){echo "value='".$_GET['titre']."'";}?>>
                    <label for="genre">Genre</label>
                    <select name="genre" id="genre">
                        <?php
                            $FICHIER_BD = "../../BD";
                            $db = new PDO('sqlite:' . $FICHIER_BD);
                            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $req = $db->prepare("SELECT * FROM genre");
                            $req->execute();
                            echo "<option value='Tous'>Tous</option>";
                            while ($row = $req->fetch()) {
                                if (isset($_GET['genre'])) {
                                    if($_GET['genre']==$row['genre_name']){
                                        echo "<option value='".$row['genre_name']."' selected>".$row['genre_name']."</option>";
                                    }else{
                                        echo "<option value='".$row['genre_name']."'>".$row['genre_name']."</option>";
                                    }
                                } else {
                                    echo "<option value='" . $row['genre_name'] . "'>" . $row['genre_name'] . "</option>";
                                }
                            }
                            $db = null;
                        ?>

                    </select>
                    <label for="prix">Prix</label>
                    <div class="range">
                        <div class="range-slider">
                            <span class="range-selected"></span>
                        </div>
                        <div class="range-input">
                            <input type="range" class="min" min="0" max="200" <?php if(isset($_GET['min'])){echo "value='".$_GET['min']."'";}else{echo "value='50'";}?>>
                            <input type="range" class="max" min="0" max="200" <?php if(isset($_GET['max'])){echo "value='".$_GET['max']."'";}else{echo "value='150'";}?>>
                        </div>
                        <div class="range-price">
                            <label for="min">Min</label>
                            <input type="number" name="min" <?php if(isset($_GET['min'])){echo "value='".$_GET['min']."'";}else{echo "value='50'";}?>>
                            <label for="max">Max</label>
                            <input type="number" name="max" <?php if(isset($_GET['max'])){echo "value='".$_GET['max']."'";}else{echo "value='150'";}?>>
                        </div>
                    </div>
                    <script>
                        let rangeMin = 10;
                        const range = document.querySelector(".range-selected");
                        const rangeInput = document.querySelectorAll(".range-input input");
                        const rangePrice = document.querySelectorAll(".range-price input");

                        rangeInput.forEach((input) => {
                            input.addEventListener("input", (e) => {
                                let minRange = parseInt(rangeInput[0].value);
                                let maxRange = parseInt(rangeInput[1].value);
                                if (maxRange - minRange < rangeMin) {
                                    if (e.target.className === "min") {
                                        rangeInput[0].value = maxRange - rangeMin;
                                    } else {
                                        rangeInput[1].value = minRange + rangeMin;
                                    }
                                } else {
                                    rangePrice[0].value = minRange;
                                    rangePrice[1].value = maxRange;
                                    range.style.left = (minRange / rangeInput[0].max) * 100 + "%";
                                    range.style.right = 100 - (maxRange / rangeInput[1].max) * 100 + "%";
                                }
                            });
                        });

                        rangePrice.forEach((input) => {
                            input.addEventListener("input", (e) => {
                                let minPrice = rangePrice[0].value;
                                let maxPrice = rangePrice[1].value;
                                if (maxPrice - minPrice >= rangeMin && maxPrice <= rangeInput[1].max) {
                                    if (e.target.className === "min") {
                                        rangeInput[0].value = minPrice;
                                        range.style.left = (minPrice / rangeInput[0].max) * 100 + "%";
                                    } else {
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
                 if (isset($_GET['submit']) && $_GET['submit'] == 'Filtrer' && isset($_GET['titre']) && isset($_GET['genre']) && isset($_GET['min']) && isset($_GET['max'])) {
                     header("Location: index.php?titre=".$_GET['titre']."&genre=".$_GET['genre']."&min=".$_GET['min']."&max=".$_GET['max']);
                 }

                ?>

            </section>
            <?php include("../template/contact.php"); ?>

        </article>

    </main>
</body>


</html>