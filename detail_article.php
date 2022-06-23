<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de l'article de blog</title>
    <link href="styles.css" rel="stylesheet">
</head>
<body>
<p><img src="blog.png"></p>
<p><a class="button" href='liste_article.php'>Retour à la liste des articles</a></p>
<?php
include_once('utils.php');

// Étape 1 : récupérer les paramètres d'URL : $_GET
$id = $_GET['id'];

// Étape 2 :
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

// récupération de l'article

$rqt = "SELECT utilisateur.email, article.* FROM utilisateur JOIN article ON utilisateur.id = article.id_utilisateur WHERE article.id=:id";
try {

    $requete_preparee = $bdd->prepare($rqt);
    $requete_preparee->bindParam(':id', $id);
    $requete_preparee->execute();
    $article = $requete_preparee->fetch(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    // On affiche les erreurs relative à la BDD SEULEMENT EN DEV!!!!!!
    echo $e->getMessage();
    http_response_code(500);
    exit;
}

// Affichage des données
echo "<h1>{$article['titre']}</h1>";

    $date = date("d-m-Y", strtotime($article['created_at']));
    echo "<p>Publié par <strong>{$article['email']}</strong> en date du <strong>$date</strong></p>";

    if (!empty($article['updated_at'])) {
        $date = date("d-m-Y", strtotime($article['updated_at']));
        echo "<p><em>Mis à jour le $date</em></p>";
    }
    echo "<blockquote><p>{$article['corps']}</p></blockquote>";
    if(empty($_SESSION['profil'])) {
        echo "<p>Pour ajouter un nouveau commentaire, ou modifier un de vos commentaires, veuillez vous <a href='login_form.php'>connecter</a></p>";
    } else {
        //echo "<a class='button' id='display_form1' href='#'>Laisser un commentaire</a>";
        echo "<button class='button' id='display_form1'>Laisser un commentaire</button>";
    }
    echo '<hr>';
?>
    <div id="commentform1" class="d-none">
        <form action="comment_create.php" method="post" >
            <input type="hidden" name="id_article" value="<?= $id ?>">
            <p><label for="texte">Commentaire :</label></p>
            <textarea id="texte" name="texte" rows="5" cols="100" required></textarea>
            <p><button type="submit">Valider le commentaire</button>
        </form>
    </div>

<?php
$rqt = "SELECT utilisateur.email, commentaire.* FROM utilisateur JOIN commentaire ON commentaire.id_utilisateur = utilisateur.id WHERE id_article=:id ORDER BY created_at DESC;";
try {

    $requete_preparee = $bdd->prepare($rqt);
    $requete_preparee->bindParam(':id', $id);
    $requete_preparee->execute();
    $commentaires = $requete_preparee->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    // On affiche les erreurs relative à la BDD SEULEMENT EN DEV!!!!!!
    echo $e->getMessage();
    http_response_code(500);
    exit;
}

// Affichage des données
echo empty($commentaires) ? '<h1>Pas encore de commentaires !</h1>': '<h1>Tous les commentaires de l\'article</h1>';
foreach($commentaires as $commentaire) {
    echo "<h3>➤ COMMENTAIRE</h3>";
    echo "<blockquote><p>{$commentaire['texte']}</p></blockquote>";
    $date = date("d-m-Y", strtotime($commentaire['created_at']));
    echo "<p>Publié par <strong>{$commentaire['email']}</strong> en date du <strong>$date</strong></p>";
    if (!empty($commentaire['updated_at'])) {
        $date = date("d-m-Y", strtotime($commentaire['updated_at']));
        echo "<p><em>Mis à jour le $date</em></p>";
    } ?>
    <?php if(!empty($_SESSION['profil']) && $commentaire['email'] === $_SESSION['profil']['email']) : ?>
   
    <button id="btnedit<?= $commentaire['id'] ?>">Modifier mon commentaire</button>
    <div id="editcommentform<?= $commentaire['id'] ?>" class="d-none">
        <form action="comment_edit.php" method="post" >
            <input type="hidden" name="id_article" value="<?= $commentaire['id_article'] ?>">
            <input type="hidden" name="id_comment" value="<?= $commentaire['id'] ?>">
            <p><label for="texte">Commentaire :</label></p>
            <textarea id="texte" name="texte" rows="5" cols="100" required><?= $commentaire['texte'] ?></textarea>
            <p><button type="submit">Valider la modification</button>
        </form>
    </div>
    <script>
        document.querySelector("#btnedit<?= $commentaire['id'] ?>").addEventListener("click", function(){
        document.querySelector("#editcommentform<?= $commentaire['id'] ?>").classList.remove("d-none");
        });
    </script>
    <?php endif; ?>
    <?php
    echo '<hr>';    
}?>
<p><a class="button" href='liste_article.php'>Retour à la liste des articles</a></p>
<script>
    document.querySelector("#display_form1").addEventListener("click", function(){
    document.querySelector("#commentform1").classList.remove("d-none");
    });    
</script>
</body>
</html>