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
                                    if($password != $confPassword) {
                                        echo "Les mots de passe ne correspondent pas";
                                    }
                                    else{
                                        // Connexion à la base de données
                                        $FICHIER_BD = "../../BD";
                                        //$db = new PDO('sqlite:' . $FICHIER_BD);
                                        $db = new PDO("mysql:host=lakartxela;dbname=mheriveau_bd", "mheriveau_bd", "mheriveau_bd");
                                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                        // On vérifie que le login n'est pas déjà utilisé
                                        $req = $db->prepare("SELECT count(*) FROM users WHERE login = :login");
                                        $req->execute(array('login' => $login));
                                        $row = $req->fetch();

                                        $req2 = $db->prepare("SELECT count(*) FROM users WHERE email = :email");
                                        $req2->execute(array('email' => $email));
                                        $row2 = $req2->fetch();

                                        if($row[0] == 0 && $row2[0] == 0){
                                            // On ajoute l'utilisateur à la base de données
                                            $req = $db->prepare("INSERT INTO users (login, password, email) VALUES (:login, :password, :email)");
                                            $req->execute(array('login' => $login, 'password' => $password, 'email' => $email));

                                            // On récupère l'id de l'utilisateur
                                            $req = $db->prepare("SELECT id FROM users WHERE login = :login");
                                            $req->execute(array('login' => $login));
                                            $resultat = $req->fetch();
                                            $_SESSION['id'] = $resultat['id'];
                                            $_SESSION['login'] = $_GET['login'];
                                            if(isset($_GET['pub'])){
                                                setcookie('login', $_GET['login'], time() + 365*24*3600, null, null, false, true);
                                            }
                                            // On redirige vers la page de connexion
                                            header("Location: connexion.php");
                                        }
                                        elseif($row[0] != 0){
                                            echo "Le login est déjà utilisé";
                                        }
                                        elseif($row2[0] != 0){
                                            echo "L'email est déjà utilisé";
                                        }
                                        else{
                                            echo "Une erreur est survenue";
                                        }

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


