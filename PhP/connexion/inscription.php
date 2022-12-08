<?php
 session_start();

?>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet/less" type="text/css" href="../../SCSS/connexion.scss"/>
    <link rel="stylesheet/less" type="text/css" href="../../SCSS/pallette.scss"/>
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
    <title>Inscription</title>
</head>

    <body>
        <div class="container">
            <div class="connexion">
                <div class="info-connexion">
                    <h2>Inscription</h2>
                    <form>
                        <div class="form-group">
                            <input type="file" class="form-control" name="avatar" id="avatar" placeholder="avatar" alt="Avatar">
                        </div>
                        <script>
                            function upload(){
                            }
                        </script>
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
                            if(isset($_GET['inscription'])){

                                // On récupère les données du formulaire
                                $login = $_GET['login'];
                                $password = $_GET['password'];
                                $confPassword = $_GET['confPassword'];
                                $email = $_GET['email'];

                                // On vérifie que les champs ne sont pas vides
                                if(!empty($login) && !empty($password) && !empty($confPassword) && !empty($email)){
                                    // On vérifie que les mots de passe sont identiques
                                    if($password == $confPassword){
                                        // On vérifie que l'email est valide
                                        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                                            include '../BD/BD.php';
                                            $query = $db->prepare("SELECT * FROM users WHERE login = :login");
                                            $query->execute(['login' => $login]);
                                            $result = $query->fetch();
                                            if($result){
                                                echo "L'utilisateur existe déjà";
                                            }else{
                                                // Envoie d'un mail de confirmation
                                                $to = $email;
                                                $subject = "Confirmation d'inscription";
                                                $message = "Bonjour, vous venez de vous inscrire sur le site CDVente. Pour confirmer votre inscription, veuillez saisir le code suivant : ";
                                                $code = rand(100000, 999999);
                                                $message .= $code;
                                                $message .= " . Si vous n'êtes pas à l'origine de cette inscription, veuillez ignorer ce mail.";
                                                $message = wordwrap($message, 70, "\r \n");
                                                $headers = "From: cdVente@iutbayonne.univ-pau.fr";
                                                mail($to, $subject, $message, $headers);
                                                $_SESSION['code'] = $code;
                                                $_SESSION['login'] = $login;
                                                $_SESSION['password'] = hash('sha256', $password);
                                                $_SESSION['email'] = $email;
                                                header('Location: confirmation.php');
                                            }
                                        }else{
                                            echo "L'email n'est pas valide";
                                        }
                                    }else{
                                        echo "Les mots de passe ne sont pas identiques";
                                    }
                                }else{
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


