<?php

/*******************************
 * Ce fichier permet de savoir si l'utilisateur est connecté
 * Il peut se faire de deux manières:
 * - L'utilisateur est connecté via la session
 * - L'utilisateur est connecté via les cookies
 ********************************/

function estConnecte()
{

    if (isset($_SESSION['login']) ||  isset($_COOKIE['login'])) {
        if (isset($_COOKIE['login']) && !isset($_SESSION['login'])) {
            $_SESSION['login'] = $_COOKIE['login'];
        }
        return true;
    } else {
        return false;
    }
}
