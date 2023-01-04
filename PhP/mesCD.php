<?php
session_start();

include 'BD/BD.php';
?>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet/less" type="text/css" href="../SCSS/mesCD.scss"/>
    <link rel="stylesheet/less" type="text/css" href="../SCSS/pallette.scss"/>
    <link rel="stylesheet/less" type="text/css" href="../SCSS/nav.scss"/>
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
    <title>Mes CD</title>
</head>
<body>
    <?php
        include '../template/nav.php';
    ?>

    <main>
        <!-- Ajout d'un CD -->
        <article class="formulaireAjout">
            <h3>Ajouter votre CD</h3>

            <form enctype="multipart/form-data" action="mesCD.php" method="post">

                <label>Importer une image : </label>
                <input type="hidden" name="MAX_FILE_SIZE" value="250000" />
                <input type="file" name="fic" size=50 />
                <label>Ou généré une image : </label>
                <button type="button" onclick="generate()">Générer une image</button>
                <script>
                    function generate(){
                        if (!document.getElementById('description').value == ""){
                            window.location.href='mesCD.php?description='+document.getElementById('description').value;
                        }else {
                            alert("Veuillez remplir la description");
                        }
                    }
                </script>
                <?php
                if (isset($_GET['description'])){
                    echo "<label>Image obtenue : </label>";
                }
                include 'dall-e.php';
                ?>
                <label>Titre : </label>
                <input type="text" name="titre" />
                <label>Artiste : </label>
                <input type="text" name="artiste" />
                <label>Genre : </label>
                <select name="genre">
                    <?php
                    

                    $requete = "SELECT * FROM genre";
                    $resultat = $db->query($requete);
                    $genres = $resultat->fetchAll();

                    foreach ($genres as $genre) {
                        echo "<option value='" . $genre['genre_name'] . "'>" . $genre['genre_name'] . "</option>";
                    }
                    ?>
                </select>
                <label>Description : </label>
                <textarea name="description" id="description" rows="1" cols="1"></textarea>
                <label>Prix : </label>
                <input type="number" name="prix" />
                <label>Quantité : </label>
                <input type="number" name="quantite" />

                <?php
                function transfert(){

                    $ret        = false;
                    $img_blob   = '';
                    $img_taille = 0;
                    $img_type   = '';
                    $img_nom    = '';
                    $taille_max = 250000;

                    // On vérifie si l'utilisateur a généré une image ou en a importé une
                    if (isset($_SESSION['url'])){
                        $ret = true;
                    }else{
                        $ret = is_uploaded_file($_FILES['fic']['tmp_name']);
                    }

                    if (!$ret) {
                        echo "Problème de transfert";
                        return false;
                    }
                    elseif (isset($_SESSION['url'])){
                        $img_nom = $_SESSION['description'];
                        $img_blob = "";
                        $img_taille = strlen($img_blob);
                        $img_type = 'image/jpeg';
                    }else{
                        // Le fichier a bien été reçu
                        $img_taille = $_FILES['fic']['size'];

                        if ($img_taille > $taille_max) {
                            echo "Trop gros !";
                            return false;
                        }

                        $img_type = $_FILES['fic']['type'];
                        $img_nom  = $_FILES['fic']['name'];
                    }

                    // 3 - Connexion à la base de données ;
                    include 'BD/BD.php';

                    // Vérification de doublon
                    $sql = "SELECT * FROM CD WHERE titre = :titre AND auteur = :artiste";
                    $req = $db->prepare($sql);
                    $req->execute(array('titre' => $_POST['titre'], 'artiste' => $_POST['artiste']));
                    $row = $req->fetch();

                    if ($row) {
                        echo "Ce CD existe déjà !";
                        return false;
                    }
                    else{
                        // 4 - Lecture du contenu du fichier dans une variable ;
                        if (isset($_SESSION['url'])){

                            // Récupération des dimensions de l'image
                            $image_info = getimagesize($_SESSION['url']);
                            $image_width = $image_info[0];
                            $image_height = $image_info[1];

                            // Création d'une image GD à partir du contenu de l'image
                            $image = imagecreatefromstring(file_get_contents($_SESSION['url']));


                            unset($_SESSION['url']);
                            unset($_SESSION['description']);
                        }else{

                            $image_info = getimagesize($_FILES['fic']['tmp_name']);
                            $image_width = $image_info[0];
                            $image_height = $image_info[1];

                            $image = imagecreatefromstring(file_get_contents($_FILES['fic']['tmp_name']));
                        }

                        
                        // Réduction de la taille de l'image
                        $new_width = $image_width / 2;
                        $new_height = $image_height / 2;
                        $scaled_image = imagescale($image, $new_width, $new_height);

                        // Enregistrement de l'image réduite dans un fichier temporaire
                        imagejpeg($scaled_image, 'image.jpg');

                        // Récupération du contenu du fichier temporaire et ajout à la base de données
                        $img_blob = file_get_contents('image.jpg');

                        // Suppression du fichier temporaire
                        unlink('image.jpg');

                        // 5 - Préparation de la requête d'insertion ;
                        $req = $db->prepare("INSERT INTO CD (genre, titre, auteur, prix, image, description, quantite, idUser) VALUES (:genre, :titre, :artiste, :prix, :image, :description, :quantite, :idUser)");

                        // 6 - Exécution de la requête ;
                        $req->execute(array(
                            'genre' => $_POST['genre'],
                            'titre' => $_POST['titre'],
                            'artiste' => $_POST['artiste'],
                            'prix' => $_POST['prix'],
                            'image' => $img_blob,
                            'description' => $_POST['description'],
                            'quantite' => $_POST['quantite'],
                            'idUser' => $_SESSION['id']
                        ));

                        // 7 - Fermeture de la connexion à la base de données ;
                        $db = null;
                    }
                    return true;
                }
                ?>

                <?php
                if (isset($_POST['titre']) && isset($_POST['artiste']) && isset($_POST['prix']) && isset($_FILES['fic']) && isset($_POST['description']) && isset($_POST['quantite'])) {
                    transfert();
                }
                ?>

                <input type="submit" value="Envoyer" />
            </form>
        </article>

        <!-- Filtre -->
        <section class="filtre">
            <h2>Filtre</h2>
            <form>
                <label for="auteur">Auteur:</label>
                <input type="text" name="titre" id="titre" <?php if(isset($_GET['titre'])){echo "value='".$_GET['titre']."'";}?>>
                <label for="genre">Genre :</label>        
                <select name="genre" id="genre">
                    <?php
                    

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
                <label for="prix">Prix:</label>
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


        <!-- Liste des CD -->
        <article class="shop-grid">
            <?php
            include 'BD/BD.php';
            if(isset($_GET['titre']) && isset($_GET['genre']) && isset($_GET['min']) && isset($_GET['max'])){
            
                if($_GET['genre']=='Tous'){
                    $req = $db->prepare("SELECT * FROM CD where prix >= :min and prix <= :max and auteur like :titre and idUser = :idUser");
                    $req->execute(array(
                        'titre' => '%'.$_GET['titre'].'%',
                        'min' => $_GET['min'],
                        'max' => $_GET['max'],
                        'idUser' => $_SESSION['id']
                    ));
                }else{
                    $req = $db->prepare("SELECT * FROM CD where prix >= :min and prix <= :max and genre = :genre and auteur like :titre and idUser = :idUser");
                    $req->execute(array(
                    'titre' => '%'.$_GET['titre'].'%',
                    'min' => $_GET['min'],
                    'max' => $_GET['max'],
                    'genre' => strval($_GET['genre']),
                    'idUser' => $_SESSION['id']
                ));
                }

            }else{
                $req = $db->prepare("SELECT * FROM CD where idUser = :idUser");
                $req->execute(array(
                    'idUser' => $_SESSION['id']
                ));
            }
            $req->execute();


            while ($row = $req->fetch()) {
                echo "<section class='shop-item' \"'>";
                echo "<img src='afficheImage.php?id=" . $row['id'] . "' /> ";
                echo "<div class='shop-item-details'>";
                echo "<h3>" . $row['titre'] . "</h3>";
                echo "<p>" . $row['auteur'] . "</p>";
                echo "</div>";
                echo "<button type='button' onclick='window.location.href=\"GererCD/removeCD.php?id=" . $row['id'] . "\"'>Supprimer</button>";
                echo "</section>";
            }



            $db = null;
            ?>
        </article>

    </main>
</body>
</html>
