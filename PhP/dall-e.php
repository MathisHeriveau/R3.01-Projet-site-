<?php

/*******************************
 * Ce fichier permet de générer une image à partir d'une description
 * 
 * Il se déroule de la manière suivante:
 * - On récupère la description
 * - On génère l'image
 * - On affiche l'image
 ********************************/

if (isset($_GET['description'])) {

    // Récupération de la description
    $uneDesc = $_GET['description'];
    // Génération de l'image
    $ch = curl_init();
    // Connexion à l'API
    curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/images/generations"); // URL de l'API
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Retourner le résultat
    curl_setopt($ch, CURLOPT_POST, 1); // Requête POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, "{
        \"prompt\": \"$uneDesc\",
        \"n\": 1,
        \"size\": \"1024x1024\" 
        }"); // Description, nombre d'images à générer, taille de l'image
    // En-têtes
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json', // Type de contenu
        'Authorization: Bearer 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' // Clé API

    ));
    try {
        $result = curl_exec($ch); // Résultat de la requête
        // Gestion des erreurs
        if (curl_errno($ch)) { 
            echo 'Error:' . curl_error($ch);
        }
        // Fermeture de la connexion
        curl_close($ch);

        // Récupération de l'URL de l'image
        $tab = json_decode($result, true);
        $url = $tab['data'][0]['url'];

        // Affichage de l'imageg
        echo "<img src='$url' alt='image générée'>";

        // Enregistrement de l'image dans la session
        $_SESSION['url'] = $url;
        $_SESSION['description'] = $uneDesc;

    } catch (Exception $e) {
        echo 'Exception reçue : ',  $e->getMessage(), "\n";
    }
}
