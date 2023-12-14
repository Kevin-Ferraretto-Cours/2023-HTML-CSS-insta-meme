<?php
session_start();
require_once 'PDO.php';

$commentaire = $_POST['comment'];
$currentPage = $_POST['from'];

if ($conn === null) {
} else {
    if ($_SESSION) {
        $stmt = $conn->prepare("INSERT INTO commentaires VALUES (null,:id_contenu,:id_user,:messages,:date_of_publi);");
        $stmt->execute(
            [
                'id_contenu' => $_POST['id'],
                'id_user' => $_SESSION['user'][0],
                'messages' => $commentaire,
                'date_of_publi' => date('Y-m-d H:i:s')
            ]
        );
        header("Location: $currentPage");
    } else {
        header("Location: Login.php");
    }
}
