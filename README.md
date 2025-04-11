# InstaMeme

Une plateforme web interactive pour découvrir, partager et interagir avec des memes, développée avec des technologies web standards.

## 📋 Description

InstaMeme est une plateforme sociale conçue pour les amateurs de memes. Ce projet permet aux utilisateurs de créer un compte, parcourir une collection de memes humoristiques, interagir avec le contenu via des likes et des commentaires, et partager leurs propres créations. Développé avec PHP, HTML, CSS et une base de données MySQL, ce projet représente une introduction complète au développement web full-stack.

## ✨ Fonctionnalités

- **Système d'authentification complet**
  - Inscription avec validation des données
  - Connexion sécurisée
  - Gestion des sessions utilisateur
  
- **Gestion des memes**
  - Affichage d'une galerie de memes sur la page d'accueil
  - Téléchargement et partage de nouveaux memes
  - Visualisation détaillée de chaque meme sur une page dédiée
  
- **Interactions sociales**
  - Système de likes
  - Section commentaires pour chaque meme
  - Compteur de vues

- **Profils utilisateurs**
  - Pages de profil personnalisées
  - Historique des memes partagés
  - Collection de memes favoris

- **Interface responsive**
  - Adaptation à tous les types d'appareils
  - Design moderne et intuitif

## 🚀 Installation

```bash
# Cloner le dépôt
git clone https://github.com/Kevin-Ferraretto-Cours/2023-HTML-CSS-insta-meme.git

# Accéder au répertoire
cd instameme

# Configuration de la base de données
# 1. Créer une base de données MySQL
# 2. Importer le fichier SQL fourni (database.sql)
# 3. Configurer les paramètres de connexion dans PDO.php
```

### Prérequis

- Serveur web (Apache/Nginx)
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur

### Configuration de la base de données

Modifiez le fichier `PDO.php` avec vos informations de connexion :

```php
<?php
try {
    $conn = new PDO("mysql:dbname=instameme;host=127.0.0.1", 'root', '');
} catch (PDOException $e) {
    $conn = null;
    $erreur = "Erreur de connexion à la BDD : " . $e->getMessage();
    echo $erreur;
}
?>
```

## 🎯 Utilisation

1. Lancez votre serveur web local (XAMPP, WAMP, etc.)
2. Accédez au projet via votre navigateur : `http://localhost/instameme`
3. Créez un compte utilisateur ou connectez-vous
4. Explorez les memes, laissez des commentaires, et partagez vos propres créations

## 🛠️ Technologies utilisées

- **PHP** - Logique côté serveur et gestion des données
- **MySQL** - Stockage des données (utilisateurs, memes, commentaires, likes)
- **HTML5** - Structure des pages
- **CSS3** - Mise en page et animations

## 📈 Roadmap

- [ ] Système de modération pour le contenu inapproprié
- [ ] Notifications pour les interactions
- [ ] Système de tags et de recherche
- [ ] Mode sombre
- [ ] API RESTful pour les développeurs

## 📜 Licence

Ce projet est distribué sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus d'informations.
