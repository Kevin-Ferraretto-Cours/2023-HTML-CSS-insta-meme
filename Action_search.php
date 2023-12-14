<?php
session_start();
require_once 'PDO.php';

if ($conn === null) {
} else {
    $stmt = $conn->prepare("SELECT utilisateurs.id, utilisateurs.pseudo from utilisateurs WHERE pseudo=:user");
    $stmt->execute(
        [
            'user' => $_POST['Search']
        ]
    );
    $user = $stmt->fetch();

    if ($user != null) {
        $user = $_POST['Search'];
        header("Location: User.php?pseudo=$user");
    } else {
        header("Location: index.php");
    }
}
