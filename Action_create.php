<?php
session_start();
require_once 'PDO.php';

$stmt = $conn->prepare("SELECT
                        COUNT(contenus.id) AS nbr_contenus
                        FROM
                        contenus;");
$stmt->execute();
$content = $stmt->fetch();

if ($conn === null) {
} else {
    if ($_SESSION) {
        if (isset($_POST['id'])) {
            $currentPage = $_POST['from'];
            $stmt = $conn->prepare("SELECT * FROM contenus WHERE id=:id_contenu;");
            $stmt->execute(
                [
                    'id_contenu' => $_POST['id']
                ]
            );
            $contenu = $stmt->fetch();
            $stmt = $conn->prepare("INSERT INTO contenus VALUES (null,:id_user,:description,:chemin_image,:date_of_publi);");
            $stmt->execute(
                [
                    'id_user' => $_SESSION['user'][0],
                    'description' => $_POST['comment'],
                    'chemin_image' => $contenu['chemin_image'],
                    'date_of_publi' => date('Y-m-d H:i:s')
                ]
            );
            header("Location: $currentPage");
        } else {
            $target_dir = "meme" . DIRECTORY_SEPARATOR;
            $name = $_FILES["file"]["name"];
            $target_file = $target_dir . basename($_FILES["file"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES["file"]["tmp_name"]);
                if ($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                $file_extension = strrchr($_FILES["file"]["name"], ".");
                $name = uniqid() . $file_extension;
                $target_file = $target_dir . basename($name);
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
                if (true) {
                    $stmt = $conn->prepare("INSERT INTO contenus VALUES (null,:id_user,:description,:chemin_image,:date_of_publi);");
                    $stmt->execute(
                        [
                            'id_user' => $_SESSION['user'][0],
                            'description' => $_POST['comment'],
                            'chemin_image' => $name,
                            'date_of_publi' => date('Y-m-d H:i:s')
                        ]
                    );
                    echo $_FILES["file"]["tmp_name"] . PHP_EOL;
                    echo $target_file;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
            header("Location: index.php");
        }
    } else {
        header("Location: Login.php");
    }
}
