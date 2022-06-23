<?php session_start() ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="styles.css" rel="stylesheet">
</head>
<body>
<p><img src="blog.png"></p>

<?php if(empty($_SESSION['profil'])) : ?>
   
    <a class="button" href="login_form.php">Se connecter</a>
    <a class="button" href="liste_article.php">Consulter les articles</a><br/>
    <hr>
    <h1>Dashboard du blog</h1>
    <blockquote><p>
        Pour ajouter/modifier :<br/>
        ➤ un article,</br>
        ➤ ou un commentaire sur un article, merci de vous connecter !</p></blockquote>
<?php else : ?>
   
    <p>
        <a class="button" href="liste_article.php">Consulter les articles</a> 
        <a class="button" href="liste_commentaire.php">Editer mes commentaires</a> 
        <a class="button" href="deconnexion.php">Déconnexion</a>
    </p>
    <h1>Dashboard du blog</h1>
    <h3>Vous vous êtes connecté avec succès !<br/>Bienvenue <em><?= $_SESSION['profil']['email'] ?></em></h3>
<?php endif; ?>

</body>
</html>

