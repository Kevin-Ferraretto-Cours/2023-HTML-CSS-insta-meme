<?php
header('Content-Type: application/xml; charset=utf-8');
require_once 'PDO.php';

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Page d'accueil -->
    <url>
        <loc>https://insta-meme.kevin-ferraretto.fr/index.php</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
    </url>
    
    <!-- Pages statiques -->
    <url>
        <loc>https://insta-meme.kevin-ferraretto.fr/Login.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    
    <url>
        <loc>https://insta-meme.kevin-ferraretto.fr/Register.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    
    <url>
        <loc>https://insta-meme.kevin-ferraretto.fr/Create.php</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <?php
    if ($conn !== null) {
        // Pages de contenu
        $stmt = $conn->prepare("SELECT 
            contenus.id,
            DATE_FORMAT(contenus.date_publication,'%Y-%m-%d') AS last_modified
        FROM contenus
        ORDER BY contenus.date_publication DESC");
        $stmt->execute();
        
        foreach ($stmt as $row) {
            echo "\n    <url>\n";
            echo "        <loc>https://insta-meme.kevin-ferraretto.fr/Content.php?id=" . $row['id'] . "</loc>\n";
            echo "        <changefreq>weekly</changefreq>\n";
            echo "        <priority>0.8</priority>\n";
            echo "        <lastmod>" . $row['last_modified'] . "</lastmod>\n";
            echo "    </url>";
        }
        
        // Pages utilisateurs
        $stmt = $conn->prepare("SELECT 
            utilisateurs.pseudo,
            MAX(DATE_FORMAT(contenus.date_publication,'%Y-%m-%d')) AS last_modified
        FROM utilisateurs
        LEFT JOIN contenus ON contenus.id_utilisateur = utilisateurs.id
        GROUP BY utilisateurs.pseudo");
        $stmt->execute();
        
        foreach ($stmt as $row) {
            echo "\n    <url>\n";
            echo "        <loc>https://insta-meme.kevin-ferraretto.fr/User.php?pseudo=" . urlencode($row['pseudo']) . "</loc>\n";
            echo "        <changefreq>weekly</changefreq>\n";
            echo "        <priority>0.7</priority>\n";
            if ($row['last_modified']) {
                echo "        <lastmod>" . $row['last_modified'] . "</lastmod>\n";
            }
            echo "    </url>";
        }
        
        // Pages de pagination
        $stmt = $conn->prepare("SELECT COUNT(*) AS nb_articles FROM contenus");
        $stmt->execute();
        $result = $stmt->fetch();
        $nbArticles = (int) $result['nb_articles'];
        $parPage = 9;
        $pages = ceil($nbArticles / $parPage);
        
        for ($page = 2; $page <= $pages; $page++) {
            echo "\n    <url>\n";
            echo "        <loc>https://insta-meme.kevin-ferraretto.fr/index.php?page=" . $page . "</loc>\n";
            echo "        <changefreq>daily</changefreq>\n";
            echo "        <priority>0.5</priority>\n";
            echo "    </url>";
        }
    }
    ?>
    
</urlset>
