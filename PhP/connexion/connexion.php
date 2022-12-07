<?php
 session_start();
?>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet/less" type="text/css" href="connexion.scss"/>
    <link rel="stylesheet/less" type="text/css" href="../SCSS/pallette.scss"/>
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
                            <a href="motdepasseoublie.php">Mot de passe oublié ?</a>
                        </div>
                        <?php
                            if(isset($_GET['connexion'])){
                                // Si tous les champs sont remplis
                                if(empty($_GET['login']) || empty($_GET['password'])){
                                    echo "<p class='erreur'>Veuillez remplir tous les champs</p>";
                                }
                                else{
                                    $FICHIER_BD = "../../../BD";
                                    $db = new PDO('sqlite:' . $FICHIER_BD);
                                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $req = $db->prepare("SELECT * FROM users where login = :login and password = :password");
                                    $req->execute(array(
                                        'login' => $_GET['login'],
                                        'password' => $_GET['password']
                                    ));
                                    $resultat = $req->fetch();

                                    if($resultat){
                                        echo "<p class='succes'>Connexion réussie</p>";
                                        $_SESSION['id'] = $resultat['id'];
                                        $_SESSION['login'] = $_GET['login'];
                                        if(isset($_GET['souvenir'])){
                                            setcookie('login', $_GET['login'], time() + 365*24*3600, null, null, false, true);

                                        }

                                        header ("Location: ../index.php");
                                    }
                                    else{
                                        echo "<p class='erreur'>Login ou mot de passe incorrect</p>";
                                    }
                                }
                            }
                        ?>

                        <button type="submit" class="btn btn-primary" name="connexion">Connexion</button>

                    </form>
                </div>
            </div>
        </div>
    </body>


</html>


