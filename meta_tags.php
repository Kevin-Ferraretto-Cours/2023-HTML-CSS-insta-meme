<?php
/**
 * Générateur de meta tags SEO pour InstaMeme
 * Usage: require_once 'meta_tags.php'; echo generateMetaTags($page, $data);
 */

function generateMetaTags($page, $data = []) {
    $baseUrl = 'https://insta-meme.kevin-ferraretto.fr';
    $siteName = 'InstaMeme';
    $defaultDescription = 'Découvrez, partagez et interagissez avec les meilleurs memes. Rejoignez notre communauté InstaMeme !';
    $defaultImage = $baseUrl . '/img/Instameme - Logo.png';
    
    // Valeurs par défaut
    $title = $data['title'] ?? $siteName;
    $description = $data['description'] ?? $defaultDescription;
    $image = $data['image'] ?? $defaultImage;
    $url = $data['url'] ?? $baseUrl;
    $type = $data['type'] ?? 'website';
    
    $output = '';
    
    // Meta tags de base
    $output .= '<meta name="description" content="' . htmlspecialchars($description) . '" />' . "\n    ";
    $output .= '<meta name="keywords" content="memes, humour, partage, communauté, images drôles, InstaMeme" />' . "\n    ";
    $output .= '<meta name="author" content="Kevin Ferraretto" />' . "\n    ";
    $output .= '<meta name="robots" content="index, follow" />' . "\n    ";
    
    // Open Graph (Facebook, LinkedIn)
    $output .= '<meta property="og:site_name" content="' . $siteName . '" />' . "\n    ";
    $output .= '<meta property="og:title" content="' . htmlspecialchars($title) . '" />' . "\n    ";
    $output .= '<meta property="og:description" content="' . htmlspecialchars($description) . '" />' . "\n    ";
    $output .= '<meta property="og:image" content="' . htmlspecialchars($image) . '" />' . "\n    ";
    $output .= '<meta property="og:url" content="' . htmlspecialchars($url) . '" />' . "\n    ";
    $output .= '<meta property="og:type" content="' . $type . '" />' . "\n    ";
    $output .= '<meta property="og:locale" content="fr_FR" />' . "\n    ";
    
    // Twitter Cards
    $output .= '<meta name="twitter:card" content="summary_large_image" />' . "\n    ";
    $output .= '<meta name="twitter:title" content="' . htmlspecialchars($title) . '" />' . "\n    ";
    $output .= '<meta name="twitter:description" content="' . htmlspecialchars($description) . '" />' . "\n    ";
    $output .= '<meta name="twitter:image" content="' . htmlspecialchars($image) . '" />' . "\n    ";
    
    // Additional SEO
    if (isset($data['author'])) {
        $output .= '<meta name="article:author" content="' . htmlspecialchars($data['author']) . '" />' . "\n    ";
    }
    
    if (isset($data['published_time'])) {
        $output .= '<meta property="article:published_time" content="' . htmlspecialchars($data['published_time']) . '" />' . "\n    ";
    }
    
    return $output;
}
