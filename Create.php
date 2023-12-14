<?php session_start();

if ($_GET) {
    require_once 'PDO.php';
    $stmt = $conn->prepare("SELECT contenus.chemin_image FROM contenus WHERE id=:id;");
    $stmt->execute(
        [
            'id' => $_GET['id']
        ]
    );
    $create = $stmt->fetch();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="./styles.css" rel="stylesheet" />
    <title>Create</title>
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
            <div class="card-meme-create">
                <form id="create-form" method="post" action="Action_create.php" enctype="multipart/form-data">
                    <?php
                    if ($_GET) {
                        echo "<img class='meme-image' src='./meme/" . $create['chemin_image'] . "' alt='meme1' />";
                        echo "<input hidden type='text' name='file' value=" . $create['chemin_image'] . " />";
                        echo "<input hidden type='text' name='from' value=" . $_GET['from'] . " />";
                        echo "<input hidden type='text' name='id' value=" . $_GET['id'] . " />";
                    } else {
                        echo "<input type='file' name='file' id='upload-file' accept='image/*, .gif' />";
                    }
                    ?>
                    <textarea name="comment" id="add-comment-create" cols="30" rows="10" placeholder="Commentez ici"></textarea>
                    <input type="submit" id="confirm" value="Ajouter" />
                </form>
            </div>
        </div>
    </main>
</body>

</html>