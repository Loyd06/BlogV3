<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des articles du blog</title>
    <link href="styles.css" rel="stylesheet">
</head>
<body>
<p><img src="blog.png"></p>
<?php
include_once('utils.php');

// connexion à la base

try {
    include_once('bdd.php');
    $bdd_options = ["PDO::ATTR_ERR_MODE" => PDO::ERRMODE_EXCEPTION];
    $bdd = new PDO("mysql:host=localhost;dbname=$db_name;port=$db_port", $db_user, $db_pass, $bdd_options);
} catch(Exception $e) {
    // On affiche les erreurs relative à la BDD SEULEMENT EN DEV!!!!!!
    echo $e->getMessage();
    http_response_code(500);
    exit;
}

// récupération des articles triés par ordre décroissant

$rqt = "SELECT utilisateur.email, article.* FROM utilisateur JOIN article ON utilisateur.id = article.id_utilisateur ORDER BY created_at DESC";
try {

    $requete_preparee = $bdd->prepare($rqt);
    $requete_preparee->execute();
    $articles = $requete_preparee->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    // On affiche les erreurs relative à la BDD SEULEMENT EN DEV!!!!!!
    echo $e->getMessage();
    http_response_code(500);
    exit;
}

// Affichage des données
echo '<p><a class="button" href="dashboard.php">Retour au Dashboard</a></p>';
echo '<h1>Liste des articles du blog</h1>';

foreach($articles as $article) {
    echo "<h2>➤ {$article['titre']}</h2>";
   

    $date = date("d-m-Y", strtotime($article['created_at']));
    echo "<p>Publié par <strong>{$article['email']}</strong> en date du <strong>$date</strong></p>";

    if (!empty($article['updated_at'])) {
        $date = date("d-m-Y", strtotime($article['updated_at']));
        echo "<p><em>Mis à jour le $date</em></p>";
    }
    $corps=substr($article['corps'],0,150);
    echo "<blockquote><p>$corps</p></blockquote>";
    echo "<a href='detail_article.php?id={$article['id']}'>Voir le détail de l'article et ses commentaires</a>";
    echo '<hr>';
}?>
<p><a class="button" href='dashboard.php'>Retour au Dashboard</a></p>
</body>
</html>