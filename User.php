<?php session_start();
require_once 'PDO.php';
$pseudo = null;
if ($_GET) {
    $pseudo = $_GET['pseudo'];
} else {
    $pseudo = $_SESSION["user"][1];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="./styles.css" rel="stylesheet" />
    <title>User</title>
    <link rel="apple-touch-icon" sizes="57x57" href="img/favicon/apple-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="60x60" href="img/favicon/apple-icon-60x60.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="img/favicon/apple-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="img/favicon/apple-icon-76x76.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="img/favicon/apple-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="img/favicon/apple-icon-120x120.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="img/favicon/apple-icon-144x144.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="img/favicon/apple-icon-152x152.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-icon-180x180.png" />
    <link rel="icon" type="image/png" sizes="192x192" href="img/favicon/android-icon-192x192.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="96x96" href="img/favicon/favicon-96x96.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png" />
    <link rel="manifest" href="img/favicon/manifest.json" />
    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta name="msapplication-TileImage" content="img/favicon/ms-icon-144x144.png" />
    <meta name="theme-color" content="#ffffff" />
</head>

<body>
    <?php require_once 'header.php'; ?>
    <main>
        <div class="rangement">
            <?php
            $stmt = $conn->prepare("SELECT
                contenus.id,
                contenus.chemin_image
            FROM
                contenus
            INNER JOIN
                utilisateurs
            ON
                contenus.id_utilisateur=utilisateurs.id
            WHERE
                utilisateurs.pseudo=:pseudo;
            ");
            $stmt->execute(
                [
                    'pseudo' => $pseudo
                ]
            );
            foreach ($stmt as $row) {
            ?>
                <div class="user-card-meme">
                    <div class="card-meme-img">
                        <?php
                        echo "<a href='Content.php?id=" . $row['id'] . "'>";
                        echo "<img class='meme-image' src='./meme/" . $row['chemin_image'] . "' alt='meme1' />";
                        echo "</a>";
                        ?>
                    </div>
                    <div class="card-meme-button">
                        <div class="like">
                            <?php
                            if ($_SESSION) {
                                $stat = $conn->prepare("SELECT
	                                *
                                    FROM
                                    likes
                                    WHERE 
                                    likes.id_contenu=:id_contenus AND likes.id_utilisateur=:id_user
                                    ;");
                                $stat->execute(
                                    [
                                        'id_contenus' => $row['id'],
                                        'id_user' => $_SESSION['user'][0]
                                    ]
                                );
                                $count = $stat->rowCount();
                                if ($count == 1) {
                                    echo "<a class='liked-button' href='Action_unlike.php?id=" . $row['id'] . "&from=User.php?pseudo=" . $pseudo . "'>Aimer</a>";
                                } else {
                                    echo "<a class='like-button' href='Action_like.php?id=" . $row['id'] . "&from=User.php?pseudo=" . $pseudo . "'>Aimer</a>";
                                }
                            } else {
                                echo "<a class='like-button' href='Login.php'>Aimer</a>";
                            }
                            ?>
                        </div>
                        <div class="share">
                            <?php
                            echo "<a class='share-button' href='Create.php?id=" . $row['id'] . "&from=User.php?pseudo=" . $pseudo . "'>Partager</a>";
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
</body>

</html>