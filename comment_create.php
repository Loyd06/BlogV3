<?php 
session_start();
include_once('utils.php');

    $id_article = $_POST['id_article'];

    // 1-  Traiter les champs de formulaire
    if(empty($_POST['texte'])) {
        // Informer que le champs est vide
        redirect_with_error("detail_article.php?id=$id_article", "empty");
    }
    $texte = $_POST['texte'];
   
   
// Connexion à la base de donnée :
require_once('bdd.php');
try {
    $bdd_options = ["PDO::ATTR_ERR_MODE" => PDO::ERRMODE_EXCEPTION];
    $bdd = new PDO("mysql:host=localhost;dbname=$db_name;port=$db_port", $db_user, $db_pass, $bdd_options);

} catch(Exception $e) {
    echo $e->getMessage();
    exit;
}

// Préparation de la requête d'insertion dans la base de données

$rqt = "INSERT INTO commentaire(texte, created_at, id_utilisateur, id_article) VALUES (:texte, :created_at, :id_utilisateur, :id_article);";

try {
    //$id_utilisateur = $_SESSION['profil']['id'];
    $requete_preparee = $bdd->prepare($rqt);

    // Associer les paramètres :
    $requete_preparee->bindParam(":texte", $texte, PDO::PARAM_STR);
    $requete_preparee->bindParam(":created_at", date('Y-m-d'),PDO::PARAM_STR);
    $requete_preparee->bindParam(":id_utilisateur", $_SESSION['profil']['id'],PDO::PARAM_INT);
    $requete_preparee->bindParam(":id_article", $id_article, PDO::PARAM_INT);
    $requete_preparee->execute();
} catch (Exception $e) {
 
    echo $e->getMessage();
    exit;
}

header("Location: detail_article.php?id=$id_article");
