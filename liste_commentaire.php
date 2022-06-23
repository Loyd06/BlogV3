
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des commentaires de l'utilisateur</title>
    <link href="styles.css" rel="stylesheet">
</head>
<body>
<p><img src="blog.png"></p>
<p><a class="button" href='dashboard.php'>Retour au Dashboard</a></p>
<?php

include_once('utils.php');
if(empty($_SESSION['profil']['email'])){
    redirect_with_error("login_form.php","auth_requise");
}
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

$rqt = "SELECT * FROM commentaire WHERE id_utilisateur = :id ORDER BY created_at DESC";
try {

    $requete_preparee = $bdd->prepare($rqt);
    $requete_preparee->bindParam(':id', $_SESSION['profil']['id'], PDO::PARAM_INT);
    $requete_preparee->execute();
    $commentaires = $requete_preparee->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    // On affiche les erreurs relative à la BDD SEULEMENT EN DEV!!!!!!
    echo $e->getMessage();
    http_response_code(500);
    exit;
}

// Affichage des données
if (empty($commentaires)){
    echo "Hello {$_SESSION['profil']['email']}<br>";
    echo "Vous n'avez pas encore écrit de commentaires !<br/>";
    echo "Retour à la <a href='liste_article.php'>liste des articles</a>";
} else {
echo '<h1>Liste de tous mes commentaires</h1>';
    foreach($commentaires as $commentaire) {
        echo "<h3>➤ COMMENTAIRE</h3>";
        echo "<blockquote><p>{$commentaire['texte']}</p></blockquote>";
       
        $date = date("d-m-Y", strtotime($commentaire['created_at']));
        echo "<p>Publié par <strong>{$_SESSION['profil']['email']}</strong> en date du <strong>$date</strong></p>";

        if (!empty($commentaire['updated_at'])) {
            $date = date("d-m-Y", strtotime($commentaire['updated_at']));
            echo "<p><em>Mis à jour le $date</em></p>";
        }
        echo "<a href='detail_article.php?id={$commentaire['id_article']}'>Voir le détail de l'article</a>";      
        echo '<hr>';
    }
}
?>
</body>
</html>