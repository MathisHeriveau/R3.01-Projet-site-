<?php

/*******************************
 * Ce fichier permet de déconnecter l'utilisateur
 * 
 * Il se déroule de la manière suivante:
 * - On détruit la session
 * - On redirige vers la page d'accueil
 ********************************/

session_start();
session_destroy();
header("Location: ../index.php");
