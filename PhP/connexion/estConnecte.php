<?php

/*******************************
 * Ce fichier permet de savoir si l'utilisateur est connecté
 * Il peut se faire de deux manières:
 * - L'utilisateur est connecté via la session
 * - L'utilisateur est connecté via les cookies
 ********************************/

function estConnecte()
{
    // On vérifie si l'utilisateur est connecté via la session ou les cookies
    if (isset($_SESSION['login']) ||  isset($_COOKIE['login'])) {
        // On vérifie si l'utilisateur est connecté via les cookies
        if (isset($_COOKIE['login']) && !isset($_SESSION['login'])) {
            $_SESSION['login'] = $_COOKIE['login'];
        }
        return true;
    } 
    // Si l'utilisateur n'est pas connecté
    else {
        return false;
    }
}
