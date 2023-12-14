<?php session_start();
require_once 'PDO.php';

// On détermine sur quelle page on se trouve
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $currentPage = (int) strip_tags($_GET['page']);
} else {
    $currentPage = 1;
}

// On détermine le nombre total d'articles
$sql = 'SELECT COUNT(*) AS nb_articles FROM `contenus`;';

// On prépare la requête
$stmt = $conn->prepare($sql);

// On exécute
$stmt->execute();

// On récupère le nombre d'articles
$result = $stmt->fetch();

$nbArticles = (int) $result['nb_articles'];

// On détermine le nombre d'articles par page
$parPage = 9;

// On calcule le nombre de pages total
$pages = ceil($nbArticles / $parPage);

// Calcul du 1er article de la page
$premier = ($currentPage * $parPage) - $parPage;

$sql = 'SELECT
 *
FROM
 likes
 WHERE id_contenu=56;';

// On prépare la requête
$stmt = $conn->prepare($sql);

// On exécute
$stmt->execute();

// On récupère le nombre d'articles
$result = $stmt->fetch();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="./styles.css" rel="stylesheet" />
    <title>Home</title>
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
                    contenus.chemin_image,
                    contenus.description,
                    utilisateurs.pseudo,
                    likes2.nb_likes,
                    DATE_FORMAT(contenus.date_publication,'%Y/%m/%d %H:%i:%s') AS date_by_format
                FROM
                    contenus
                INNER JOIN
                    utilisateurs ON
                    utilisateurs.id=contenus.id_utilisateur
                LEFT JOIN (
                    SELECT
                        id_contenu, COUNT(id_utilisateur) AS nb_likes
                    FROM
                        likes
                    GROUP BY
                        id_contenu
                ) AS
                    likes2
                ON 
                    likes2.id_contenu=contenus.id
                GROUP BY
                    contenus.id,
                    contenus.chemin_image,
                    utilisateurs.pseudo
                ORDER BY
                    date_by_format DESC
                LIMIT :premier, :parpage
            ;");
            $stmt->bindValue(':premier', $premier, PDO::PARAM_INT);
            $stmt->bindValue(':parpage', $parPage, PDO::PARAM_INT);
            $stmt->execute();
            foreach ($stmt as $row) { ?>
                <div class="card-meme">
                    <div class="card-meme-user">
                        <?php
                        echo "<a href='User.php?pseudo=" . $row['pseudo'] . "'>" . $row['pseudo'] . "</a>";
                        ?>
                    </div>
                    <div class="card-meme-img">
                        <?php
                        echo "<a class='meme-position' href='Content.php?id=" . $row['id'] . "'>";
                        ?>
                        <picture>
                            <?php
                            echo "<img class='meme-image' src='./meme/" . $row['chemin_image'] . "' alt='meme1' />";
                            ?>
                        </picture>
                        </a>
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
                                    echo "<a class='liked-button' href='Action_unlike.php?id=" . $row['id'] . "&from=index.php?page=" . $currentPage . "'>Aimer</a>";
                                } else {
                                    echo "<a class='like-button' href='Action_like.php?id=" . $row['id'] . "&from=index.php?page=" . $currentPage . "'>Aimer</a>";
                                }
                            } else {
                                echo "<a class='like-button' href='Login.php'>Aimer</a>";
                            }
                            ?>
                            <!-- <svg class="" title="Like Heart SVG File" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="#f785b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg> -->
                        </div>
                        <div class="share">
                            <?php
                            echo "<a class='share-button' href='Create.php?id=" . $row['id'] . "&from=index.php?page=" . $currentPage . "'>Partager</a>";
                            ?>

                        </div>
                    </div>
                    <div class="card-meme-liked">
                        <p class="liked">
                            <?php
                            if ($row['nb_likes'] == null) {
                                echo "<u><b>Aimer par:</b></u> 0 personne";
                            } else {
                                echo "<u><b>Aimer par:</b></u> " . $row['nb_likes'] . " personne";
                            }
                            ?>
                        </p>
                    </div>
                    <div class="card-meme-description">
                        <p class="description">
                            <?php
                            echo "<u><b>Description:</b></u> " . $row['description'];
                            ?>
                        </p>
                    </div>
                    <div class="card-meme-commentaire">
                        <p class="commentaire">
                            <b><u><b>Commentaire:</b></u></b>
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
                                    'id' => $row['id']
                                ]
                            );
                            foreach ($statement as $ligne) {
                                echo "<p class='com'>" . $ligne['pseudo'] . ": " . $ligne['message'] . "</p>";
                            } ?>
                        </div>
                        <?php
                        echo "<form class='add-commentaire'  method='POST' action='Action_comment.php'>";
                        ?>
                        <input type="text" name="comment" class="usrcomment" placeholder="Commenter ici" />
                        <?php
                        echo "<input hidden type='text' name='id' value='" . $row['id'] . "' />";
                        echo "<input hidden type='text' name='from' value='index.php?page=" . $currentPage . "' />";
                        ?>

                        <input type="submit" class="usrcomment-submit" value="Commenter" />
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="nav-pagination">
            <nav>
                <ul class="pagination">
                    <?php
                    // Lien vers la page précédente (désactivé si on se trouve sur la 1ère page)
                    if ($currentPage > 1) {
                        echo "<li class='page-item'>";
                        echo "<a href='./index.php?page=" . ($currentPage - 1) . "' class='page-link'>Précédente</a>";
                        echo "</li>";
                    }
                    for ($page = 1; $page <= $pages; $page++) {
                        // Lien vers chacune des pages (activé si on se trouve sur la page correspondante)
                        echo "<li class='page-item'>";
                        echo "<a href='./index.php?page=" . $page . "' class='page-link'>$page</a>";
                        echo "</li>";
                    }
                    // Lien vers la page suivante (désactivé si on se trouve sur la dernière page) 
                    if ($currentPage < $pages) {
                        echo "<li class='page-item'>";
                        echo "<a href='./index.php?page=" . ($currentPage + 1) . "' class='page-link'>Suivante</a>";
                        echo "</li>";
                    }
                    ?>
                </ul>
            </nav>
        </div>


    </main>
</body>

</html>