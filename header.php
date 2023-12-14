<header>
    <nav class="navbar">
        <div class="navbar-logo">
            <a href="index.php">
                <img id="navbar-logo-icon" src="./img/Instameme - Logo.png" alt="logo" />
            </a>
            <a class="bouton" href="index.php">Accueil</a>
        </div>
        <div class="navbar-middle">
            <form action="Action_search.php" method="POST">
                <div class="search-box">
                    <input type="text" name="Search" class="search-txt" placeholder="Tapez pour rechercher" />
                    <input class="search-btn" type="submit" value="&#x1F50D;" />
                </div>
            </form>
        </div>
        <div class="navbar-button">
            <?php if (isset($_SESSION['user'])) { ?>
                <a href='Create.php' class='bouton'>Créér</a>
                <a href='User.php' class='bouton'>Profil</a>
                <a href='Action_logout.php' class='bouton'>Déconnexion</a>
            <?php } else { ?>
                <a href='Register.php' class='bouton'>Inscription</a>
                <a href='Login.php' class='bouton'>Connexion</a>
            <?php } ?>
        </div>
    </nav>
</header>