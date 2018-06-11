<?php

    require_once('inc/init.php');

    unset($_SESSION['membre']);
    //On supprime seulement la partie membre de la SESSION et on garde le reste

    header('location:index.php');

?>