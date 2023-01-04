<?php

/*******************************
 * Ce fichier permet changer le mot de passe
 * 
 * Il se déroule de la manière suivante:
 * - On récupère demander le mail de l'utilisateur (mdpOublie.php)
 * - On envoie un mail avec un lien contenant un token (mdpOublie.php)
 * - L'utilisateur copie le token et le colle dans le champ (changerMdp.php)
 * - Il entre son nouveau mot de passe (changerMdp.php)
 * - On vérifie que le token est le bon (changerMdp.php)
 * - On change le mot de passe (changerMdp.php)
 * - On redirige vers la page de connexion (changerMdp.php)
 ********************************/


session_start();

$errer = ""; // Variable pour afficher les erreurs

if (isset($_POST['email'])) {
    $_SESSION['email'] = $_POST['email'];
}

// Si on a cliqué sur le bouton "changer mot de passe"
if (isset($_GET['changerMdp'])) {

    include '../BD/BD.php';

    // On récupère les données du formulaire
    $email = $_SESSION['email'];
    $token = $_GET['token'];
    $password = $_GET['password'];
    $password2 = $_GET['password2'];

    // On vérifie que le token est le bon et que les mots de passe correspondent
    if (isset($_GET['password']) && isset($_GET['password2']) && ($_GET['password'] == $_GET['password2'] && $token == $_SESSION['token'])) {
        // On vérifie que les mots de passe correspondent
        if ($password == $password2) {
            // On hash le mot de passe
            $password = hash('sha256', $_GET['password']);
            // On met à jour le mot de passe
            $req = $db->prepare('UPDATE users SET password = :password WHERE email = :email');
            $req->execute(array(
                'password' => $password,
                'email' => $email
            ));
            // On redirige vers la page de connexion
            header("Location: connexion.php");
        } else {
            // On affiche un message d'erreur
            $errer = "Les mots de passe ne correspondent pas";
        }
    } else {
        // On affiche un message d'erreur
        $errer = "Les mots de passe ne correspondent pas ou le token est incorrect";
    }
}

?>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet/less" type="text/css" href="../../SCSS/connexion.scss" />
    <link rel="stylesheet/less" type="text/css" href="../../SCSS/pallette.scss" />
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
    <title>Changer mdp</title>

</head>

<body>
    <!-- Formulaire de connexion -->
    <div class="container">
        <div class="connexion">
            <div class="info-connexion">
                <h2>Changer mot de passe</h2>
                <form>
                    <div class="form-group">
                        <input type="text" class="form-control" name="token" id="token" placeholder="Token">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Nouveau mot de passe">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password2" id="password2" placeholder="Confirmer mot de passe">
                    </div>
                    <?php if ($errer != "") { ?>
                        <p class="erreur"><?php echo $errer; ?></p>
                    <?php } ?>
                    <button type="submit" class="btn btn-primary" name="changerMdp">Changer mot de passe</button>
                </form>
            </div>
        </div>
    </div>
</body>