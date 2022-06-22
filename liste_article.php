<?php session_start();?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des articles de Blog</title>
    <link href= "styles.css" rel="stylesheet"/>
</head>
<body>

<?php
include_once('utils.php');

 // Connexion à la base de donnée : 
 require_once('bdd.php');
 try {
     $bdd_options = ["PDO::ATTR_ERR_MODE" => PDO::ERRMODE_EXCEPTION];
     $bdd = new PDO("mysql:host=localhost;dbname=$db_name;port=$db_port", $db_user, $db_pass, $bdd_options); 

 } catch(Exception $e) {
     echo $e->getMessage();
     exit;
 }
// Récupération des articles en base

// Étape 3 récupération de l'utilisateur (*) à partir de son email 

$rqt = "SELECT utilisateur.email, article.* FROM utilisateur JOIN article ON utilisateur.id=article.id_utilisateur ORDER BY created_at DESC";
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
echo '<h1>liste des dernier articles</h1>';
foreach ($articles as $article){
    echo "<h2>{$article['titre']}</h2>";
    echo "<h4><blockquote><p>{$article['corps']}</p></blockquote></h4>";
    echo "<h4>Publié par {$article['email']}</h4>";
}
