<?php
session_start();
$_SESSION = array();//-------Supprimer les variables de session------
session_destroy();//---------Supprimer la session---------
header('Location: connexion.php');
?>