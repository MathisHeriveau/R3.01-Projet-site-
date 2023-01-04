<?php
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

if (isset($_GET['ModifierPhoto'])){
    if (transfert()){
        echo "Transfert réussi";
    }
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet/less" type="text/css" href="../SCSS/profil.scss"/>
    <link rel="stylesheet/less" type="text/css" href="../SCSS/nav.scss"/>
    <link rel="stylesheet/less" type="text/css" href="../SCSS/pallette.scss"/>
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
    <title>Profil</title>
</head>

<body>
    <?php include("../template/nav.php"); ?>
    <main>
        <div class="profil">
            <div class="profil__header">
                <h1 class="profil__header__title">Profil</h1>
                <div class="profil__header__image">
                    <?php
                        if($image == null){
                            
                            echo '<img src="../Images/Avatar.png" alt="profil">';
                        }else{
                            echo "<img src='afficheImage.php?login=1'> ";
                        }
                    ?>
                </div>
            </div>
            <div class="profil__info">
                <div class="profil__info__login">
                    <h2 class="profil__info__login__title">Login</h2>
                    <p class="profil__info__login__text"><?php echo $login ?></p>
                </div>
                <div class="profil__info__email">
                    <h2 class="profil__info__email__title">Email</h2>
                    <p class="profil__info__email__text"><?php echo $email ?></p>
                </div>
            </div>

            <form enctype="multipart/form-data" action="profil.php?ModifierPhoto=" method="post">
                <label>Importer une image : </label>
                <input type="hidden" name="MAX_FILE_SIZE" value="250000" />
                <input type="file" name="fic" size=50 />
                <button type="submit" name="ModifierPhoto" class="profil__bouton__submit">Modifier</button>
                <a href="../PhP/deconnexion.php" class="profil__bouton__deconnexion">Deconnexion</a>
            </form>
            <?php
            function transfert(){
                // creation d'une alerte
                echo "<script>alert('Votre photo de profil a bien été modifié');</script>";
                $ret        = false;
                $img_blob   = '';
                $img_taille = 0;
                $img_type   = '';
                $img_nom    = '';
                $taille_max = 250000;
                $ret = is_uploaded_file($_FILES['fic']['tmp_name']);


                if (!$ret) {
                    echo "Problème de transfert";
                    return false;
                }
                else{
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


