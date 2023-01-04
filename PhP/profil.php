<?php

/*******************************
 * Ce fichier permet de modifier le profil de l'utilisateur
 * 
 * Il se déroule de la manière suivante:
 * - On récupère les informations de l'utilisateur
 * - On affiche les informations de l'utilisateur
 * - On affiche le formulaire de modification
 * 
 * Si l'utilisateur clique sur le bouton "Modifier", alors:
 * - On récupère les informations du formulaire
 * - On remplace l'ancienne image par la nouvelle
 * - On met à jour les informations de l'utilisateur
 ********************************/


session_start();
// On redirige vers la page d'accueil
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
}

// Connexion à la base de données
include '../PhP/BD/BD.php';

// On récupère les informations de l'utilisateur
$req = $db->prepare("Select * from users where id = :id");
$req->execute(array(
    'id' => $_SESSION['id']
));
$req->execute();
$row = $req->fetch();

$login = $row['login'];
$password = $row['password'];
$email = $row['email'];
$image = $row['image'];

// Si on a cliqué sur le bouton "Modifier"
if (isset($_GET['ModifierPhoto'])) {
    // On met à jour l'image de l'utilisateur
    if (transfert()) {
        echo "Transfert réussi";
    }
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet/less" type="text/css" href="../SCSS/nav.scss" />
    <link rel="stylesheet/less" type="text/css" href="../SCSS/pallette.scss" />
    <link rel="stylesheet/less" type="text/css" href="../SCSS/profil.scss" />
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
    <title>Profil</title>
</head>

<body>
    <?php include("nav.php"); ?>
    <main>
        <!-- Affichage du profil -->
        <div class="profil">
            <h1>Profil</h1>
            <div class="profil__header">
                <?php
                if ($image == null) {

                    echo '<img src="../Images/Avatar.png" alt="profil">';
                } else {
                    $id = $_SESSION['id'];
                    echo "<img src=\"afficheImage.php?login=" . $id . "\">";
                }
                ?>
            </div>
            <div class="profil__info">
                <div class="profil__info__item">
                    <h2>Login</h2>
                    <p><?php echo $login ?></p>
                </div>
                <div class="profil__info__item">
                    <h2>Email</h2>
                    <p><?php echo $email ?></p>
                </div>
            </div>

            <!-- Formulaire de modification -->

            <form enctype="multipart/form-data" action="profil.php?ModifierPhoto=" method="post">
                <label>Importer une image : </label>
                <input type="hidden" name="MAX_FILE_SIZE" value="250000" />
                <input type="file" name="fic" size=50 />
                <button type="submit" name="ModifierPhoto">Modifier la photo de profil</button>
                <div class="groupe_bouton">
                    <a href="connexion/mdpOublie.php">Changer le mot de passe</a>
                    <a href="connexion/deconnexion.php">Deconnexion</a>

                </div>

            </form>
            <?php

            // Fonction qui permet de transférer l'image dans la base de données déja décrite dans le fichier "mesCD.php"
            function transfert()
            {
                // creation d'une alerte
                $ret        = false;
                $img_blob   = '';
                $img_taille = 0;
                $img_type   = '';
                $img_nom    = '';
                $taille_max = 250000;
                $ret = is_uploaded_file($_FILES['fic']['tmp_name']);


                if (!$ret) {
                    return false;
                } else {
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

                // 4 - Lecture du contenu du fichier dans une variable ;
                $image_info = getimagesize($_FILES['fic']['tmp_name']);
                $image_width = $image_info[0];
                $image_height = $image_info[1];

                $image = imagecreatefromstring(file_get_contents($_FILES['fic']['tmp_name']));
                $new_width = $image_width / 2;
                $new_height = $image_height / 2;
                $scaled_image = imagescale($image, $new_width, $new_height);

                // Enregistrement de l'image réduite dans un fichier temporaire
                imagejpeg($scaled_image, 'image.jpg');

                // Récupération du contenu du fichier temporaire et ajout à la base de données
                $img_blob = file_get_contents('image.jpg');

                // Suppression du fichier temporaire
                unlink('image.jpg');


                // 5 - Préparation de la requête d'insertion (SQL) ;
                $req = $db->prepare("UPDATE users SET image = :image WHERE id = :id");
                $req->execute(array(
                    'image' => $img_blob,
                    'id' => $_SESSION['id']
                ));

                // 6 - Exécution de la requête ;
                $req->execute();

                // 7 - Fermeture de la connexion à la base de données ;
                $db = null;
            }
            ?>

        </div>
    </main>



</body>

</html>