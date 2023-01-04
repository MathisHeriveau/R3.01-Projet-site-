<?php

/*******************************
 * Ce fichier permet d'inscrire un utilisateur
 * 
 * Il se déroule de la manière suivante:
 * - On récupère le login, le mot de passe et l'email (inscription.php)
 * - On vérifie que le login n'est pas déjà utilisé (inscription.php)
 * - On vérifie que l'email n'est pas déjà utilisé (inscription.php)
 * - On envoie un mail avec un lien contenant un code (inscription.php)
 * - L'utilisateur copie le code et le colle dans le champ (confirmation.php)
 * - On vérifie que le code est le bon (confirmation.php)
 * - On inscrit l'utilisateur (confirmation.php)
 * - On redirige vers la page de connexion (confirmation.php)
 ********************************/


session_start();

// Si on a cliqué sur le bouton "inscription"
if (isset($_GET['inscription'])) {
    // On récupère les données du formulaire
    $code = $_GET['code'];
    // On vérifie que le code est le bon
    if ($code == $_SESSION['code']) {
        // On inscrit l'utilisateur
        include '../BD/BD.php';
        $login = $_SESSION['login'];
        $password = $_SESSION['password'];
        $email = $_SESSION['email'];
        $req = $db->prepare('INSERT INTO users(login, password, email) VALUES(:login, :password, :email)');
        $req->execute(array(
            'login' => $login,
            'password' => $password,
            'email' => $email
        ));
        // Suppression des variables de session et de la session
        $_SESSION = array();
        session_destroy();

        // Envoie sur la page de connexion
        header("Location: connexion.php");
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet/less" type="text/css" href="../../SCSS/connexion.scss" />
    <link rel="stylesheet/less" type="text/css" href="../../SCSS/pallette.scss" />
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
    <title>Confirmation inscription</title>
</head>

<body>
    <!-- Formulaire d'inscription qui est sur la meme base que le formulaire de connexion -->
    <div class="container">
        <div class="connexion">
            <div class="info-connexion">
                <h2>Confirmation inscription</h2>
                <form>
                    <div class="form-group">
                        <input type="text" class="form-control" name="code" id="code" placeholder="Code">
                    </div>
                    <?php
                    // Si le code est incorrect
                    if (isset($_GET['inscription']) && $code != $_SESSION['code']) { ?>
                        <p class="erreur">Code incorrect</p>
                    <?php } ?>
                    <button type="submit" class="btn btn-primary" name="inscription">Confirmer l'inscription</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>