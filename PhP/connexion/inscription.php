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
    header('Location: confirmation.php');
}

?>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet/less" type="text/css" href="../../SCSS/connexion.scss" />
    <link rel="stylesheet/less" type="text/css" href="../../SCSS/pallette.scss" />
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
    <title>Inscription</title>
</head>

<body>
    <!-- Formulaire d'inscription qui est sur la meme base que le formulaire de connexion -->
    <div class="container">
        <div class="connexion">
            <div class="info-connexion">
                <h2>Inscription</h2>
                <form>
                    <div class="form-group">
                        <input type="text" class="form-control" name="login" id="login" placeholder="Login">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Mot de passe">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="confPassword" id="confPassword" placeholder="Confirmer le mot de passe">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                    </div>
                    <div class="form-bouton">
                        <div class="souvenir">
                            <input type="checkbox" name="pub" id="pub">
                            <label for="pub">Recevoir des annonces</label>
                        </div>
                        <a href="connexion.php">Je possede déja un compte ?</a>
                    </div>
                    <?php
                    // Si on a cliqué sur le bouton "inscription"
                    if (isset($_GET['inscription'])) {

                        // On récupère les données du formulaire
                        $login = $_GET['login'];
                        $password = $_GET['password'];
                        $confPassword = $_GET['confPassword'];
                        $email = $_GET['email'];

                        // On vérifie que les champs ne sont pas vides
                        if (!empty($login) && !empty($password) && !empty($confPassword) && !empty($email)) {
                            // On vérifie que les mots de passe sont identiques
                            if ($password == $confPassword) {
                                // On vérifie que l'email est valide
                                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    // On vérifie que le login n'est pas déjà utilisé
                                    include '../BD/BD.php';
                                    $query = $db->prepare("SELECT * FROM users WHERE login = :login and email = :email");
                                    $query->execute(['login' => $login, 'email' => $email]);
                                    $result = $query->fetch();
                                    // Si le login est déjà utilisé
                                    if ($result) {
                                        echo "L'utilisateur existe déjà";
                                    } 
                                    // Sinon on envoie un mail de confirmation
                                    else {
                                        // Envoie d'un mail de confirmation
                                        $to = $email;
                                        $subject = "Confirmation d'inscription";
                                        $message = "Bonjour,\nVous venez de vous inscrire sur le site CDVente. ";
                                        $code = rand(100000, 999999);
                                        $message .= "Pour confirmer votre inscription, veuillez saisir le code suivant : " . $code . ".\n";
                                        $message .= "Si vous n'êtes pas à l'origine de cette inscription, veuillez ignorer ce mail.";
                                        $message = wordwrap($message, 70, "\r \n");
                                        $headers = "From: cdVente@iutbayonne.univ-pau.fr";
                                        if (mail($to, $subject, $message, $headers)) {
                                            echo "Un mail de confirmation vous a été envoyé.";
                                        } else {
                                            echo "Une erreur est survenue lors de l'envoie du mail.";
                                        }

                                        // On stocke les données dans des variables de session
                                        $_SESSION['code'] = $code;
                                        $_SESSION['login'] = $login;
                                        $_SESSION['password'] = hash('sha256', $password);
                                        $_SESSION['email'] = $email;
                                    }
                                } 
                                // Sinon on affiche un message d'erreur
                                else {
                                    echo "L'email n'est pas valide";
                                }
                            } 
                            // Sinon on affiche un message d'erreur
                            else {
                                echo "Les mots de passe ne sont pas identiques";
                            }
                        } 
                        // Sinon on affiche un message d'erreur
                        else {
                            echo "Veuillez remplir tous les champs";
                        }
                    }
                    ?>

                    <button type="submit" class="btn btn-primary" name="inscription">Inscription</button>

                </form>
            </div>
        </div>
    </div>
</body>


</html>