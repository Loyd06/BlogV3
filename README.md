Initialisation d'un dépot sur GitHub BlogV3
https://github.com/Loyd06/BlogV3.git

Création du fichier en local README.md
Initialisation du dépot local htdoc/BlogV3 avec Git Init

Copie des fichiers de BlogV2 dans le répertoire local

Création de la base de données 
Mise à jour de la configuration dans bdd.php

Création d'un utilisateur : loydmatthew42@gmail.com P@ssw0rd

Mise en place d'une gestion des sessions
Ajout de <?php session_start();?>
On ajoute une variable de session profil lorsque l'utilisateur se connecte 
    // 4.2 : si ok on l'envoi sur une page de succes (genre dashboard)
$_SESSION['profil']['id']=$user['id'];
$_SESSION['profil']['email']=$email;
header('Location: dashboard.php');

Création d'une page pour lister les articles et les commentaires attachés
liste_article.php

Création d'une feuille de Style
Styles.CSS

