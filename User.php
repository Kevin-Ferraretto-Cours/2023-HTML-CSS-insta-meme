<?php 
session_start();
require_once 'PDO.php';
require_once 'meta_tags.php';

$pseudo = null;
if ($_GET) {
    $pseudo = $_GET['pseudo'];
} else {
    $pseudo = $_SESSION["user"][1];
}

// Récupérer les infos utilisateur pour SEO
$stmt = $conn->prepare("SELECT 
    utilisateurs.id,
    utilisateurs.pseudo,
    utilisateurs.date_inscription,
    COUNT(DISTINCT contenus.id) as nb_memes,
    COUNT(DISTINCT likes.id_contenu) as nb_likes
FROM utilisateurs
LEFT JOIN contenus ON contenus.id_utilisateur = utilisateurs.id
LEFT JOIN likes ON likes.id_utilisateur = utilisateurs.id
WHERE utilisateurs.pseudo = :pseudo
GROUP BY utilisateurs.id");
$stmt->execute(['pseudo' => $pseudo]);
$userInfo = $stmt->fetch();

// Préparer les meta tags
$baseUrl = 'https://insta-meme.kevin-ferraretto.fr';
$pageTitle = htmlspecialchars($pseudo) . " - Profil utilisateur | InstaMeme";
$pageDescription = "Découvrez les " . ($userInfo['nb_memes'] ?? 0) . " memes partagés par " . htmlspecialchars($pseudo) . " sur InstaMeme. Membre depuis " . date('Y', strtotime($userInfo['date_inscription'] ?? 'now'));
$pageUrl = $baseUrl . "/User.php?pseudo=" . urlencode($pseudo);

$metaData = [
    'title' => $pageTitle,
    'description' => $pageDescription,
    'url' => $pageUrl,
    'type' => 'profile'
];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <?php echo generateMetaTags('user', $metaData); ?>
    
    <link href="./styles.css" rel="stylesheet" />
    <link rel="canonical" href="<?php echo $pageUrl; ?>" />
    
    <title><?php echo $pageTitle; ?></title>
    
    <!-- JSON-LD Structured Data pour Profil -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ProfilePage",
        "mainEntity": {
            "@type": "Person",
            "name": "<?php echo htmlspecialchars($pseudo); ?>",
            "url": "<?php echo $pageUrl; ?>",
            "interactionStatistic": [
                {
                    "@type": "InteractionCounter",
                    "interactionType": "https://schema.org/CreateAction",
                    "userInteractionCount": <?php echo $userInfo['nb_memes'] ?? 0; ?>
                },
                {
                    "@type": "InteractionCounter",
                    "interactionType": "https://schema.org/LikeAction",
                    "userInteractionCount": <?php echo $userInfo['nb_likes'] ?? 0; ?>
                }
            ]
        }
    }
    </script>
    
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
        <div class="rangement" itemscope itemtype="https://schema.org/Person">
            <meta itemprop="name" content="<?php echo htmlspecialchars($pseudo); ?>" />
            <meta itemprop="url" content="<?php echo $pageUrl; ?>" />
            
            <?php
            $stmt = $conn->prepare("SELECT
                contenus.id,
                contenus.chemin_image,
                contenus.description
            FROM
                contenus
            INNER JOIN
                utilisateurs
            ON
                contenus.id_utilisateur=utilisateurs.id
            WHERE
                utilisateurs.pseudo=:pseudo
            ORDER BY
                contenus.date_publication DESC;
            ");
            $stmt->execute(['pseudo' => $pseudo]);
            
            foreach ($stmt as $row) {
            ?>
                <div class="user-card-meme" itemscope itemtype="https://schema.org/ImageObject">
                    <div class="card-meme-img">
                        <?php
                        echo "<a href='Content.php?id=" . $row['id'] . "'>";
                        echo "<img class='meme-image' src='./meme/" . htmlspecialchars($row['chemin_image']) . "' alt='" . htmlspecialchars($row['description']) . "' itemprop='contentUrl' loading='lazy' />";
                        echo "</a>";
                        ?>
                        <meta itemprop="name" content="<?php echo htmlspecialchars($row['description']); ?>" />
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
                                    echo "<a class='liked-button' href='Action_unlike.php?id=" . $row['id'] . "&from=User.php?pseudo=" . urlencode($pseudo) . "'>Aimer</a>";
                                } else {
                                    echo "<a class='like-button' href='Action_like.php?id=" . $row['id'] . "&from=User.php?pseudo=" . urlencode($pseudo) . "'>Aimer</a>";
                                }
                            } else {
                                echo "<a class='like-button' href='Login.php'>Aimer</a>";
                            }
                            ?>
                        </div>
                        <div class="share">
                            <?php
                            echo "<a class='share-button' href='Create.php?id=" . $row['id'] . "&from=User.php?pseudo=" . urlencode($pseudo) . "'>Partager</a>";
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
</body>

</html>