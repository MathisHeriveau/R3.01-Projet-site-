<?php
 session_start();
 
if(isset($_GET['inscription'])){
    $code = $_GET['code'];
    if($code == $_SESSION['code']){
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
        header ("Location: connexion.php");
    }
}
                            
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet/less" type="text/css" href="../../SCSS/connexion.scss"/>
    <link rel="stylesheet/less" type="text/css" href="../../SCSS/pallette.scss"/>
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
    <title>Confirmation inscription</title>
</head>
    
        <body>
            <div class="container">
                <div class="connexion">
                    <div class="info-connexion">
                        <h2>Confirmation inscription</h2>
                        <form>
                            <div class="form-group">
                                <input type="text" class="form-control" name="code" id="code" placeholder="Code">
                            </div>
                            <?php if(isset($_GET['inscription']) && $code != $_SESSION['code']){ ?>
                                <p class="erreur">Code incorrect</p>
                            <?php } ?>
                            <button type="submit" class="btn btn-primary" name="inscription">Confirmer l'inscription</button>
                        </form>
                    </div>
                </div>
            </div>
        </body>
</html>

