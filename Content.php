<?php session_start();
require_once 'PDO.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="./styles.css" rel="stylesheet" />
    <title>Content</title>
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
            <div class="card-meme-content">
                <div class="two-colums">
                    <?php
                    $stmt = $conn->prepare("SELECT
                        utilisateurs.pseudo,
                        contenus.description,
                        contenus.chemin_image,
                        likes2.nb_likes
                        FROM
                            contenus
                        INNER JOIN
                            utilisateurs
                        ON
                            utilisateurs.id=contenus.id_utilisateur
                        LEFT JOIN (
                            SELECT
                                id_contenu,
                                COUNT(id_utilisateur) AS nb_likes
                            FROM
                                likes
                            GROUP BY
                                id_contenu
                        ) AS likes2
                        ON 
                            likes2.id_contenu=contenus.id
                        WHERE
                            contenus.id=:id;");
                    $stmt->execute(
                        [
                            'id' => $_GET['id']
                        ]
                    );
                    $content = $stmt->fetch();
                    ?>
                    <div class="card-meme-img">
                        <?php
                        echo "<img class='meme-image-content' src='./meme/" . $content['chemin_image'] . "' alt='meme1' />";
                        ?>

                    </div>
                    <div class="card-meme-info">
                        <div class="card-meme-user">
                            <?php
                            echo "<a href='User.php?pseudo=" . $content['pseudo'] . "'>" . $content['pseudo'] . "</a>";
                            ?>
                        </div>
                        <div class="card-meme-description">
                            <p class="description">
                                <?php
                                echo "<u><b>Description:</b></u> " . $content['description'];
                                ?>
                            </p>
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
                                            'id_contenus' => $_GET['id'],
                                            'id_user' => $_SESSION['user'][0]
                                        ]
                                    );
                                    $count = $stat->rowCount();
                                    if ($count == 1) {
                                        echo "<a class='liked-button' href='Action_unlike.php?id=" . $_GET['id'] . "&from=Content.php?id=" . $_GET['id'] . "'>Aimer</a>";
                                    } else {
                                        echo "<a class='like-button' href='Action_like.php?id=" . $_GET['id'] . "&from=Content.php?id=" . $_GET['id'] . "'>Aimer</a>";
                                    }
                                } else {
                                    echo "<a class='like-button' href='Login.php'>Aimer</a>";
                                }
                                ?>
                            </div>
                            <div class="share">
                                <?php
                                echo "<a class='share-button' href='Create.php?id=" . $_GET['id'] . "&from=Content.php?id=" . $_GET['id'] . "'>Partager</a>";
                                ?>
                            </div>
                        </div>
                        <div class="card-meme-liked">
                            <p class="liked">
                                <?php
                                echo "<u><b>Aimer par:</b></u> " . $content['nb_likes'] . " personne";
                                ?>
                            </p>
                        </div>
                        <div class="card-meme-commentaire">
                            <p class="commentaire">
                                <u><b>Commentaire:</b></u>
                            </p>
                            <div>
                                <?php
                                $statement = $conn->prepare("SELECT
                                commentaires.message,
                                utilisateurs.pseudo,
                                DATE_FORMAT(commentaires.date_publication,'%Y/%m/%d %H:%i:%s') AS date_by_format
                            FROM
                                commentaires
                            INNER JOIN
                                utilisateurs
                            ON
                                utilisateurs.id=commentaires.id_utilisateur
                            WHERE
                                commentaires.id_contenu=:id
                            ORDER BY
                                date_by_format DESC LIMIT 3;
                            ");
                                $statement->execute(
                                    [
                                        'id' => $_GET['id']
                                    ]
                                );
                                foreach ($statement as $ligne) {
                                    echo "<p class='com'>" . $ligne['pseudo'] . ": " . $ligne['message'] . "</p>";
                                } ?>
                            </div>
                            <?php
                            echo "<form class='add-commentaire' method='POST' action='Action_comment.php'>";
                            ?>
                            <input type="text" name="comment" class="usrcomment" placeholder="Commenter ici" />
                            <?php
                            echo "<input hidden type='text' name='id' value='" . $_GET['id'] . "' />";
                            echo "<input hidden type='text' name='from' value='Content.php?id=" . $_GET['id'] . "' />";
                            ?>

                            <input type="submit" class="usrcomment-submit" value="Commenter" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>