<h1>Site de vente de CD en ligne</h1>

<h2>Présentation</h2>

<p>Dans le cadre d'un projet de notre formation, nous sommes amenés à concevoir un site de vente en ligne.</p>
<h6>Information :</h6>
<p>On souhaite proposer un site web de vente de CD en ligne.
Chaque CD possède un genre, titre, auteur/groupe, prix, une description, une quantité et une image de la pochette.</p>

<h6>Objectifs :</h6>
<ul><li>Proposer un site qui affiche l’ensemble des CD (vignette de la pochette, titre, auteur/groupe). Lors
de la sélection d’un titre, on verra la pochette en taille réelle ainsi que l’ensemble des informations
relatives au CD.</li>
<li>Proposer la fonctionnalité de sélection et de mise en panier. On simulera le paiement en vérifiant
la saisie des 16 chiffres et vérifiant que le dernier est identique au premier, et que la date de validité
est supérieure à la date du jour + 3 mois.</li>
<br>Remarque : afin de minimiser les temps de transferts, les vignettes seront de réelles vignettes
(images générées en format réduit) et non pas les images redimensionnées avec WIDTH/HEIGHT
d’IMG SRC.<br><br>
<li>Proposer un accès sécurisé avec un back-office permettant l’ajout/suppression de CDs</li>
</ul>

<h2>Installation</h2>

<p>Le projet est effectué sur l'IDE PhpStorm, il est donc nécessaire d'avoir ce dernier d'installé sur votre machine.</p>
<ul>
    <li>Etape 1 : Cloner le projet sur votre machine</li>
    <li>Etape 2 : Ouvrir le projet dans PhpStorm</li>
    <li>Etape 3 : Si ce n'est pas fait, dans le fichier situé dans PhP/BD/BD.php<br> 
        - Décommenter la ligne "$db = new PDO('sqlite:' . $path);" en enlevant les slashs <br>
        - Ajouter les slashs sur la ligne "$db = new PDO("mysql:host=lakartxela;dbname=mheriveau_bd", "mheriveau_bd", "mheriveau_bd");"  </li>
    <li><strong>A savoir !</strong> <br>
            Etant donné que le projet est en local, il est impossible de créer un compte utilisateur puisque le serveur va envoyer un mail de confirmation.<br>
</ul>

<p>Pour les étudiants ou enseignants ayant accès au serveur lakartxela, une base de données a été créé sur le server</p>
<ul>
    <li>Il suffit de me demander de modifier l'accès à la base de données pour que vous puissiez y accéder si ce n'est pas fait</li>
    <li>Si vous n'avez pas accès au serveur lakartxela, vous pouvez utiliser la base de données en local</li>
    <li>Site web sur l'url : <a href="https://lakartxela.iutbayonne.univ-pau.fr/~mheriveau/s3/PHP/R3.01-Projet-site-/PhP/">Lien vers le site</a>
</li>
</ul>
