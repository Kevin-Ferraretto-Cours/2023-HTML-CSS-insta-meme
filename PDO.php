<?php
try {
    $conn = new PDO("mysql:dbname=instameme;host=127.0.0.1", 'root', '');
} catch (PDOException $e) {
    $conn = null;
    $erreur = "Erreur de connexion à la BDD : " . $e->getMessage();
    echo $erreur;
}
