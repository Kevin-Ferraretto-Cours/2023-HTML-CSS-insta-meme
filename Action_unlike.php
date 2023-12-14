<?php
session_start();
require_once 'PDO.php';

$currentPage = $_GET['from'];

if ($conn === null) {
} else {
    if ($_SESSION) {
        $stmt = $conn->prepare("DELETE FROM likes WHERE likes.id_contenu=:id_contenu AND likes.id_utilisateur=:id_user;");
        $stmt->execute(
            [
                'id_contenu' => $_GET['id'],
                'id_user' => $_SESSION['user'][0]
            ]
        );
        header("Location: $currentPage");
    } else {
        header("Location: Login.php");
    }
}
