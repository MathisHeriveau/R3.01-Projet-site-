<?php

/*******************************
 * Ce fichier permet de connecter l'utilisateur
 * 
 * Il se déroule de la manière suivante:
 * - On récupère le login et le mot de passe (connexion.php)
 * - On vérifie que le login et le mot de passe sont corrects (connexion.php)
 * - On redirige vers la page d'accueil (connexion.php)
 * - On affiche un message d'erreur si le login ou le mot de passe est incorrect (connexion.php)
 * - On affiche un message d'erreur si tous les champs ne sont pas remplis (connexion.php)
 ********************************/


session_start();
$erreur = "";

if (isset($_GET['connexion'])) {
    // Si tous les champs sont remplis
    if (empty($_GET['login']) || empty($_GET['password'])) {
        $erreur = "Veuillez remplir tous les champs";
    } else {
        include '../BD/BD.php';
        $password = hash('sha256', $_GET['password']);
        $req = $db->prepare("SELECT * FROM users where login = :login and password = :password");
        $req->execute(array(
            'login' => $_GET['login'],
            'password' => $password
        ));
        $resultat = $req->fetch();

        if ($resultat) {
            $_SESSION['id'] = $resultat['id'];
            $_SESSION['login'] = $_GET['login'];
            if (isset($_GET['souvenir'])) {
                setcookie('login', $_GET['login'], time() + 365 * 24 * 3600, null, null, false, true);
            }

            header("Location: ../index.php");
        } else {
            $erreur = "Login ou mot de passe incorrect";
        }
    }
} else {
    session_destroy();
}
?>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet/less" type="text/css" href="../../SCSS/connexion.scss" />
    <link rel="stylesheet/less" type="text/css" href="../../SCSS/pallette.scss" />
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
    <title>Connexion</title>
</head>

<body>
    <div class="container">
        <div class="connexion">
            <div class="info-connexion">
                <h2>Connexion</h2>
                <form>
                    <div class="form-group">
                        <input type="text" class="form-control" name="login" id="login" placeholder="Login">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Mot de passe">
                    </div>
                    <div class="form-bouton">
                        <div class="souvenir">
                            <input type="checkbox" name="souvenir" id="souvenir">
                            <label for="souvenir">Se souvenir de moi</label>
                        </div>
                        <div class="oubliCreer">
                            <a href="mdpOublie.php">Mot de passe oublié ?</a>
                            <a href="inscription.php">Creer un compte ?</a>
                        </div>
                    </div>

                    <?php
                    if ($erreur != "") {
                        echo "<p class='erreur'>
                                $erreur
                                </p>";
                    }
                    ?>

                    <button type="submit" class="btn btn-primary" name="connexion">Connexion</button>

                </form>
            </div>
        </div>
    </div>
</body>


</html>