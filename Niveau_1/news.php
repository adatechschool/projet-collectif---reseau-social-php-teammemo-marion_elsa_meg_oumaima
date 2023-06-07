<?php
session_start();
// Vérifie si l'utilisateur n'est pas connecté (par exemple, en vérifiant la présence d'une variable de session)
if (!isset($_SESSION['connected_id'])) {
    header("Location: login.php"); // Redirige vers la page de connexion
    exit(); // Arrête l'exécution du reste du code
}

?>

<?php include("header.php"); ?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>MySafePLace - Actualités</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
    <div id="wrapper">
        <aside>
            <!-- <div class="initial-avatar">...</div> -->
            <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez les derniers messages de
                    toutes les utilisatrices du site.</p>
            </section>
        </aside>
        <main>
            <?php
            // Etape 1: Ouvrir une connexion avec la base de donnée.
            include("BDconnection.php");
            //verificationS
            if ($mysqli->connect_errno) {
                echo "<article>";
                echo ("Échec de la connexion : " . $mysqli->connect_error);
                echo ("<p>Indice: Vérifiez les parametres de <code>new mysqli(...</code></p>");
                echo "</article>";
                exit();
            }
            // On affiche les 5 derniers posts
            // Etape 2: Poser une question à la base de donnée et récupérer ses informations
            $laQuestionEnSql = "
    SELECT posts.content,
    users.id as author_id,
    posts.created,
    users.alias as author_name,  
    count(likes.id) as like_number,  
    GROUP_CONCAT(DISTINCT tags.label) AS taglist, GROUP_CONCAT(DISTINCT tags.id) AS tagId
    FROM posts
    JOIN users ON  users.id=posts.user_id
    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
    LEFT JOIN likes      ON likes.post_id  = posts.id 
    GROUP BY posts.id
    ORDER BY posts.created DESC  
    LIMIT 10
";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            // Vérification
            if (!$lesInformations) {
                echo "<article>";
                echo ("Échec de la requete : " . $mysqli->error);
                echo ("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                exit();
            }

            // Etape 3: Parcourir ces données et les ranger bien comme il faut dans du html
            // NB: à chaque tour du while, la variable post ci dessous reçois les informations du post suivant.
            while ($post = $lesInformations->fetch_assoc()) {
                include "articles.php";
            }
            ?>

        </main>
    </div>
    </body>

</html>