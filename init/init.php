<?php
    /*
    * Fichier qui permet de déclarer la base de données, la connecter et y accéder depuis n'importe quel fichier
    */
    require_once 'class/database.php';

    $db = new database(
        'localhost', 
        '', 
        'root',
        'maDataBase');

    $db->connect();
?>
