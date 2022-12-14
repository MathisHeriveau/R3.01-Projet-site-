<?php

/*******************************
 * Ce fichier permet de commander les CD et de simuler un paiement
 * 
 * Il se déroule de la manière suivante:
 * - On récupère les informations de l'utilisateur dans un premier formulaire
 * - On récupère les informations de la carte bancaire dans un second formulaire
 * - On vérifie que les informations sont correctes
 * - On affiche un récapitulatif de la commande
 * - On supprime les CD du panier
 * - On redirige vers la page d'accueil
 ********************************/

session_start();

// On redirige vers la page d'accueil si l'utilisateur n'est pas connecté
if (isset($_SESSION['id'])) {
    $connecte = true;
} else {
    $connecte = false;
}

// Si le panier est vide, on redirige vers la page d'accueil
include 'BD/BD.php';
$query = "SELECT * FROM panier where idUser = :id_client";
$stmt = $db->prepare($query);
$stmt->bindParam(':id_client', $_SESSION['id']);
$stmt->execute();
$result = $stmt->fetchAll();
$vide = false;
if (count($result) == 0) {
    $vide = true;
    header('Location:index.php?retour=');
}

?>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet/less" type="text/css" href="../SCSS/commander.scss" />
    <link rel="stylesheet/less" type="text/css" href="../SCSS/nav.scss" />
    <link rel="stylesheet/less" type="text/css" href="../SCSS/pallette.scss" />
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>
    <title>Commander</title>
</head>

<body>
    <?php include 'nav.php'; ?>
    <main>
        <!-- Formulaire pour récupérer les informations de l'utilisateur -->
            <form>
                <h2>Commander</h2>
                <div class="info">
                    <!-- POUR CHAQUE LABEL? SI L'UTILISATEUR N'A PAS REMPLI LE CHAMP, ON AFFICHE UN INPUT SINON LA DONNEE -->

                    <label for="nom">Nom</label>
                    <?php
                    
                    if (isset($_GET['nom'])) {
                        echo "<p>" . $_GET['nom'] . "</p>";
                    } elseif (isset($_SESSION['nom'])) {
                        echo "<p>" . $_SESSION['nom'] . "</p>";
                    } else {
                        echo "<input type='text' name='nom' id='nom' required>";
                    }
                    ?>


                    <label for="prenom">Prénom</label>
                    <?php
                    if (isset($_GET['prenom'])) {
                        echo "<p >" . $_GET['prenom'] . "</p>";
                    } elseif (isset($_SESSION['prenom'])) {
                        echo "<p >" . $_SESSION['prenom'] . "</p>";
                    } else {
                        echo "<input type='text' name='prenom' id='prenom' required>";
                    }
                    ?>


                    <label for="adresse">Adresse</label>
                    <?php
                    if (isset($_GET['adresse'])) {
                        echo "<p >" . $_GET['adresse'] . "</p>";
                    } elseif (isset($_SESSION['adresse'])) {
                        echo "<p >" . $_SESSION['adresse'] . "</p>";
                    } else {
                        echo "<input type='text' name='adresse' id='adresse' required>";
                    }
                    ?>


                    <label for="ville">Ville</label>
                    <?php
                    if (isset($_GET['ville'])) {
                        echo "<p >" . $_GET['ville'] . "</p>";
                    } elseif (isset($_SESSION['ville'])) {
                        echo "<p >" . $_SESSION['ville'] . "</p>";
                    } else {
                        echo "<input type='text' name='ville' id='ville' required>";
                    }
                    ?>


                    <label for="codePostal">Code postal</label>
                    <?php
                    if (isset($_GET['codePostal'])) {
                        echo "<p >" . $_GET['codePostal'] . "</p>";
                    } elseif (isset($_SESSION['codePostal'])) {
                        echo "<p >" . $_SESSION['codePostal'] . "</p>";
                    } else {
                        echo "<input type='text' name='codePostal' id='codePostal' required>";
                    }
                    ?>


                    <label for="pays">Pays</label>
                    <?php
                    if (isset($_GET['pays'])) {
                        echo "<p >" . $_GET['pays'] . "</p>";
                    } elseif (isset($_SESSION['pays'])) {
                        echo "<p >" . $_SESSION['pays'] . "</p>";
                    } else {
                        echo "<input type='text' name='pays' id='pays' required>";
                    }
                    ?>


                    <label for="email">Email</label>
                    <?php
                    include 'BD/BD.php';
                    $req = $db->prepare("SELECT * FROM users where id = :id");
                    $req->execute(array(
                        'id' => $_SESSION['id']
                    ));
                    $result = $req->fetch();
                    $email = $result['email'];
                    echo "<p>" . $email . "</p>";
                    ?>


                    <label for="telephone">Téléphone</label>
                    <?php
                    if (isset($_GET['telephone'])) {
                        echo "<p>" . $_GET['telephone'] . "</p>";
                    } elseif (isset($_SESSION['telephone'])) {
                        echo "<p>" . $_SESSION['telephone'] . "</p>";
                    } else {
                        echo "<input type='text' name='telephone' id='telephone' required>";
                    }
                    ?>

                    
                </div>

                <!-- On affiche le mode de paiement -->
                <div class="paiement">
                    <h3>Paiement</h3>

                    <label for="carte">Carte bancaire</label>
                    <?php
                    if (isset($_GET['paiement'])) {
                        if ($_GET['paiement'] == "carte") {
                            echo "<input type='radio' name='paiement' id='carte' value='carte' checked required>";
                        } else {
                            echo "<input type='radio' name='paiement' id='carte' value='carte' required>";
                        }
                    } else {
                        echo "<input type='radio' name='paiement' id='carte' value='carte' required>";
                    }
                    ?>

                    <label for="paypal">Paypal</label>
                    <?php
                    if (isset($_GET['paiement'])) {
                        if ($_GET['paiement'] == "paypal") {
                            echo "<input type='radio' name='paiement' id='paypal' value='paypal' checked required>";
                        } else {
                            echo "<input type='radio' name='paiement' id='paypal' value='paypal' required>";
                        }
                    } else {
                        echo "<input type='radio' name='paiement' id='paypal' value='paypal' required>";
                    }

                    ?>
                </div>
                <!-- On affiche le bouton -->
                <?php
                // Si les données sont valides, on désactive le bouton pour éviter les doublons
                if (isset($_GET['submit']) || isset($_SESSION['telephone'])) {
                    echo "<input type='submit' name='submit' value='Valider' disabled>";
                } else {
                    echo "<input type='submit' name='submit' value='Valider'>";
                }
                ?>

            </form>

        <!-- Formulaire de paiement -->

            <?php
            if (isset($_GET['submit']) || isset($_GET['numeroCarte'])) {
                
                // On affiche le formulaire de paiement
                echo "<form class='payer'>";
                echo "<h2>Paiement</h2>";
                echo "<div class='carte'>";
                echo "<label for='numeroCarte'>Numéro de carte</label>";

                // Si les données sont valides, on affiche les données
                if (paiementValide()) {
                    echo "<p>" . $_GET['numeroCarte'] . "</p>";
                    echo "<label for='dateExpiration'>Date d'expiration</label>";
                    echo "<p>" . $_GET['dateExpiration'] . "</p>";
                    echo "<label for='cryptogramme'>Cryptogramme</label>";
                    echo "<p>" . $_GET['cryptogramme'] . "</p>";
                    echo "<input type='submit' name ='payer' value='Payer' disabled>";
                    echo "</form>";
                    echo "</div>";
                } else {
                    echo "<input type='text' name='numeroCarte' id='numeroCarte' required maxlength='16' pattern='[0-9]{16}'>";
                    echo "<label for='dateExpiration'>Date d'expiration</label>";
                    echo "<input type='month' name='dateExpiration' id='dateExpiration' required>";
                    echo "<label for='cryptogramme'>Cryptogramme</label>";
                    echo "<input type='text' name='cryptogramme' id='cryptogramme' required maxlength='3' pattern='[0-9]{3}'>";
                

                    // Si les données sont pas valides, on affiche les erreurs
                    echo "<p class='erreur'>Le numéro de carte doit avoir le premier et le dernier chiffre identique</p>";
                    echo "<p class='erreur'>La date doit être supérieure à la date actuelle + 3 mois</p>";
                    echo "<input type='submit' name ='payer' value='Payer'>";
                    echo "</form>";
                    echo "</div>";
                }


                // On enregistre les données dans une session
                if (isset($_GET['submit'])) {
                    // Récupération des données dans un session
                    $_SESSION['nom'] = $_GET['nom'];
                    $_SESSION['prenom'] = $_GET['prenom'];
                    $_SESSION['adresse'] = $_GET['adresse'];
                    $_SESSION['ville'] = $_GET['ville'];
                    $_SESSION['codePostal'] = $_GET['codePostal'];
                    $_SESSION['pays'] = $_GET['pays'];
                    $_SESSION['email'] = $email;
                    $_SESSION['telephone'] = $_GET['telephone'];
                }
            }
            ?>

            <?php
            // Fonction de validation du paiement
            function paiementValide()
            {
                if (isset($_GET['numeroCarte'])) {
                    // Vérification du numéro de carte
                    if ($_GET['numeroCarte'][0] == $_GET['numeroCarte'][15]) {
                        // Vérification de la date d'expiration
                        $dateLimite = date('Y-m', strtotime('+3 months'));
                        if ($_GET['dateExpiration'] > $dateLimite) {
                            return true;
                        } else {
                            // Erreur date d'expiration situé sous le titre paiement
                            return false;
                        }
                    } else {
                        return false;
                    }
                }
            }
            ?>

        <!-- Affichage du total de la commande -->
        <?php
        if (paiementValide() && isset($_GET['payer'])) {

            // Récupération de du total de la commande
            $req = $db->prepare("SELECT * FROM panier where idUser = :id");
            $req->execute(array(
                'id' => $_SESSION['id']
            ));
            $req->execute();

            $cd = $db->prepare("SELECT * FROM CD where id = :id");

            $total = 0;
            while ($row = $req->fetch()) {
                $cd->execute(array(
                    'id' => $row['idCD']
                ));
                $cd->execute();
                $cdRow = $cd->fetch();
                $total += $cdRow['prix'] * $row['quantite'];
            }

            // Vérifier que la commande n'existe pas déjà
            $req = $db->prepare("SELECT * FROM Commande where idClient = :id");
            $req->execute(array(
                'id' => $_SESSION['id']
            ));

            // Création de la commande
            $req = $db->prepare("INSERT INTO Commande (idCommande, idClient, dateCommande, dateLivraison, adresseLivraison, prixTotal) VALUES (NULL, :id, :dateCommande, :dateLivraison, :adresseLivraison, :prixTotal)");
            $req->execute(array(
                'id' => $_SESSION['id'],
                'dateCommande' => date("Y-m-d"),
                'dateLivraison' => date("Y-m-d", strtotime("+1 week")),
                'adresseLivraison' => $_SESSION['adresse'] . ", " . $_SESSION['ville'] . ", " . $_SESSION['codePostal'] . ", " . $_SESSION['pays'],
                'prixTotal' => $total
            ));
            $idCommande = $db->lastInsertId();

            // Mettre tous les CD du panier dans CDCommande
            $req = $db->prepare("SELECT * FROM panier where idUser = :id");
            $req->execute(array(
                'id' => $_SESSION['id']
            ));
            $req->execute();


            $insert = $db->prepare("INSERT INTO CDCommande (idCommande, idCD, quantite) VALUES (:idCommande, :idCD, :quantite)");
            while ($row = $req->fetch()) {
                $insert->execute(array(
                    'idCommande' => $idCommande,
                    'idCD' => $row['idCD'],
                    'quantite' => $row['quantite']
                ));
            }

            // Supprimer le panier
            $req = $db->prepare("DELETE FROM panier where idUser = :id");
            $req->execute(array(
                'id' => $_SESSION['id']
            ));


            // Afficher le récapitulatif de la commande
            echo "<div class='recap'>";
            echo "<h2>Paiement effectué</h2>";
            echo "<h3>Récapitulatif de la commande</h3>";
            echo "<p>Commande <strong>n°" . $idCommande . "</strong></p>";
            echo "<p>Date de la commande : <strong>" . date("d/m/Y") . "</strong></p>";
            echo "<p>Date de livraison : <strong>" . date("d/m/Y", strtotime("+1 week")) . "</strong></p>";
            echo "<p>Adresse de livraison : <strong>" . $_SESSION['adresse'] . ", " . $_SESSION['ville'] . ", " . $_SESSION['codePostal'] . ", " . $_SESSION['pays'] . "</strong></p>";
            echo "<p>Prix total : <strong>" . $total . ",00€</strong></p>";

            // Afficher les CD de la commande
            $req = $db->prepare("SELECT * FROM CDCommande where idCommande = :id");
            $req->execute(array(
                'id' => $idCommande
            ));
            $req->execute();

            $CD = $db->prepare("SELECT * FROM CD where id = :id");

            echo "<table>";
            echo "<tr>";
            echo "<th>Pochette</th>";
            echo "<th>Ref</th>";
            echo "<th>Titre</th>";
            echo "<th>Auteur</th>";
            echo "<th>Quantité</th>";
            echo "<th>Prix</th>";
            echo "</tr>";
            while ($row = $req->fetch()) {
                $CD->execute(array(
                    'id' => $row['idCD']
                ));
                $CD->execute();
                $CDRow = $CD->fetch();
                echo "<tr>";
                echo "<td><img src='afficheImage.php?id=" . $CDRow['id'] . "'></td>";
                echo "<td>" . $CDRow['id'] . "</td>";
                echo "<td>" . $CDRow['titre'] . "</td>";
                echo "<td>" . $CDRow['auteur'] . "</td>";
                echo "<td>" . $row['quantite'] . "</td>";
                echo "<td>" . $CDRow['prix'] . ",00€</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";

            echo "<form action='index.php'>";
            echo "<input type='submit' name='retour' value='Retour à l accueil'>";
            echo "</form>";


            // Envoi du mail
            $to = $_SESSION['email'];
            $subject = "Confirmation de commande";
            $message = "Bonjour " . $_SESSION['prenom'] . " " . $_SESSION['nom'] . ",\n\n";
            $message .= "Votre commande n°" . $idCommande . " a bien été prise en compte.\n";
            $message .= "Vous recevrez votre commande dans les plus brefs délais.\n\n";
            $message .= "Récapitulatif de la commande :\n";
            $message .= "Commande n°" . $idCommande . "\n";
            $message .= "Date de la commande : " . date("d/m/Y") . "\n";
            $message .= "Date de livraison : " . date("d/m/Y", strtotime("+1 week")) . "\n";
            $message .= "Adresse de livraison : " . $_SESSION['adresse'] . ", " . $_SESSION['ville'] . ", " . $_SESSION['codePostal'] . ", " . $_SESSION['pays'] . "\n";
            $message .= "Prix total : " . $total . ",00€\n\n";
            $message .= "Merci de votre confiance,\n";
            $message .= "L'équipe de CDVente";
            $headers = "From: CDVente <cdVente@iutbayonne.univ-pau.fr>";
            mail($to, $subject, $message, $headers);
        }


        ?>
    </main>


</body>



</html>