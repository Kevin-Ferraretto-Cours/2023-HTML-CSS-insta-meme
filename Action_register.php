<?php
session_start();
require_once 'PDO.php';
if ($conn === null) {
} else {
    $stmt = $conn->prepare("SELECT pseudo from utilisateurs WHERE pseudo=:pseudo");
    $stmt->execute(
        [
            'pseudo' => $_POST['username'],
        ]
    );
    $user = $stmt->fetchAll();
    if (count($user) == 0) {
        $stmt = $conn->prepare("INSERT INTO utilisateurs (pseudo, mot_de_passe, date_inscription) VALUES (:pseudo, MD5(:password), :date_inscription)");
        $stmt->execute(
            [
                'pseudo' => $_POST['username'],
                'password' => $_POST['password'],
                'date_inscription' => date('Y-m-d H:i:s'),
            ]
        );

        $stmt = $conn->prepare("SELECT * from utilisateurs WHERE pseudo=:user");
        $stmt->execute(
            [
                'user' => $_POST['username']
            ]
        );
        $user = $stmt->fetch();

        $_SESSION["user"] = $user;
        header("Location: index.php");
    } else {
        header("Location: Register.php");
    }
}
