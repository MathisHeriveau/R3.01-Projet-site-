<?php
    function estConnecte(){

        if(isset($_SESSION['login']) ||  isset($_COOKIE['login'])){
            return true;
        }
        else{
            return false;
        }
    }

