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

include '../BD/BD.php';

$errer = ""; // Variable pour afficher les erreurs

// Si on a cliqué sur le bouton "changer mot de passe"
if (isset($_GET['mdpOublie'])) {
    // On récupère les données du formulaire
    $email = $_GET['email'];
    // On vérifie que le mail existe
    $req = $db->prepare('SELECT * FROM users WHERE email = :email');
    $req->execute(array(
        'email' => $email
    ));
    $resultat = $req->fetch();
    // Si le mail existe
    if ($resultat) {
        // On génère un token aléatoire
        $token = random_int(100000, 999999);
        $_SESSION['token'] = $token;
        $_SESSION['email'] = $email;

        // On envoie un mail à l'utilisateur avec un lien lui permettant de choisir un nouveau mot de passe
        $to = $email;
        $subject = "Reinitialisation de votre mot de passe\n";
        $message = "Bonjour, vous avez demandé à réinitialiser votre mot de passe.\n";
        $message = "Votre code de réinitialisation est : " . $token . ".\n";
        $message .= "Si vous n'êtes pas à l'origine de cette demande, veuillez ignorer ce mail.";
        $message = wordwrap($message, 70, "\r \n");
        $headers = "From: cdVente@iutbayonne.univ-pau.fr";
        if (mail($to, $subject, $message, $headers)) {
        } else {
            $errer = "Une erreur est survenue lors de l'envoie du mail.";
        }
        // On redirige vers la page de changement de mot de passe
        header('Location: changerMdp.php?&email=' . $_GET['email']);
    } 
    // Sinon on affiche une erreur
    else {
        $errer = "Aucun compte n'est associé à cette adresse mail.";
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
    <title>Mdp oublié</title>
</head>

<body>
    <!-- Formulaire de récupération de mot de passe sous la meme forme que la page de connexion -->
    <div class="container">
        <div class="connexion">
            <div class="info-connexion">
                <h2>Mot de passe oublié</h2>
                <form>
                    <div class="form-group">
                        <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                    </div>
                    <?php if ($errer != "") { ?>
                        <p class="erreur"><?php echo $errer; ?></p>
                    <?php } ?>
                    <button type="submit" class="btn btn-primary" name="mdpOublie">Envoyer un mail</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>