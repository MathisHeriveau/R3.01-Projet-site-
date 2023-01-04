<?php
    if(isset($_GET['description'])){

        $uneDesc = $_GET['description'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/images/generations");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{
        \"prompt\": \"$uneDesc\",
        \"n\": 1,
        \"size\": \"1024x1024\"
        }");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer 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'

        ));
        try {
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            // Récupération de l'URL de l'image
            $tab = json_decode($result, true);
            $url = $tab['data'][0]['url'];

            // Affichage de l'imageg
            echo "<img src='$url' alt='image générée'>";
            $_SESSION['url'] = $url;
            $_SESSION['description'] = $uneDesc;

        }
        catch (Exception $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }

    }


?>