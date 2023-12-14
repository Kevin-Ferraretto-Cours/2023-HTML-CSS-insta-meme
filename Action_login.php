<?php
session_start();
require_once 'PDO.php';

if ($conn === null) {
} else {
    $stmt = $conn->prepare("SELECT  * from utilisateurs WHERE pseudo=:user");
    $stmt->execute(
        [
            'user' => $_POST['username']
        ]
    );
    $user = $stmt->fetch();

    if ($user != null) {
        $password = md5($_POST['password']);
        if ($user['mot_de_passe'] == $password) {
            $_SESSION["user"] = $user;
            header("Location: index.php");
        } else {
            header("Location: Login.php");
        }
    } else {
        header("Location: Register.php");
    }
}
