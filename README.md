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

Création d'une feuille de Style
Styles.CSS

Création d'une page pour lister les articles et les commentaires attachés
liste_article.php


On effectue une requête avec jointure pour récupèrer les données de l'utilisateur et de l'article
$rqt = "SELECT utilisateur.email, article.* FROM utilisateur JOIN article ON utilisateur.id = article.id_utilisateur ORDER BY created_at DESC";
On réalise ensuite une boucle foreach pour affiché les données
Concernant le corps de l'article on affiche seulement les 150 premiers caractères, l'article complet serat affiché dans la page de détaille 
$corps=substr($article['corps'],0,150);
Pour chaque article on ajoute un lien qui permettra d'afficher le détail de l'article et ses commentaires
echo "<a href='detail_article.php?id={$article['id']}'>Voir le détail de l'article et ses commentaires</a>";
On passe en paramètres a la page détail_article l'id de l'article 

On utilise une structure alternative pour affiché la date de mise à jour de l'article si elle n'est pas NULL
if (!empty($article['updated_at'])) {
        $date = date("d-m-Y", strtotime($article['updated_at']));
        echo "<p><em>Mis à jour le $date</em></p>";
    }
On affiche les dates au formats JJMMAAAA avec la fonction date de php
$date = date("d-m-Y", strtotime($article['created_at']));
    echo "<p>Publié par <strong>{$article['email']}</strong> en date du <strong>$date</strong></p>";

AJOUT DE LA PAGE déconnexion.php

On supprime la variable de session profil
unset ($_SESSION['profil']);

Création de la page detail_article.php

On récupère l'id de l'article reçu en paramètre 
// Étape 1 : récupérer les paramètres d'URL : $_GET
$id = $_GET['id'];

On execute ensuite la requête SQL pour récupèrer en base l'article concerné
$rqt = "SELECT utilisateur.email, article.* FROM utilisateur JOIN article ON utilisateur.id = article.id_utilisateur WHERE article.id=:id";

On affiche les données de l'article (email de l'auteur, date de création, date éventuel de modification)

On utilise une structure alternative pour proposé d'ajouter ou de modifier un commentaire uniquement si l'utilisateur est connecté
Si c'est le cas on affiche un formulaire de saisie
 if(empty($_SESSION['profil'])) {
        echo "<p>Pour ajouter un nouveau commentaire, ou modifier un de vos commentaires, veuillez vous <a href='login_form.php'>connecter</a></p>";
    } else {
        //echo "<a class='button' id='display_form1' href='#'>Laisser un commentaire</a>";
        echo "<button class='button' id='display_form1'>Laisser un commentaire</button>";
    }

On effectue une nouvelle requête pour récupèrer l'ensemble des commentaires de l'article 
$rqt = "SELECT utilisateur.email, commentaire.* FROM utilisateur JOIN commentaire ON commentaire.id_utilisateur = utilisateur.id WHERE id_article=:id ORDER BY created_at DESC;";
On affiche les commentaires les plus récents en tête de liste 

Pour chaque commentaires on utilise une structure alternative pour afficher le boutton de modification de commenataires dans le cas où ce commentaire appartient a l'utilisateur commenté
<?php if(!empty($_SESSION['profil']) && $commentaire['email'] === $_SESSION['profil']['email']) : ?>

Par défaut les formulaires d'ajout ou de modification de commentaire sont masqués
.d-none {
    display:none;
}

Au clique du bouton ajout de commentaire ou modification de commentaires, une fonction JavaScript affiche le formulaire
<script>
    document.querySelector("#display_form1").addEventListener("click", function(){
    document.querySelector("#commentform1").classList.remove("d-none");
    });    
</script>

On implemente l'insertion du commentaire en base de donnés avec le script comment_create.php
On transmet au script comment_create.php l'id de l'article par l'intermédiaire d'un champ caché
<input type="hidden" name="id_article" value="<?= $id ?>">

de la même façon la mise à jour du commentaire est implémenté avec le scrit comment_edit.php
Pour des raisons de sécurités les requêtes d'insertion et de mise à jour sont des requêtes PDO paramètrés

Les valeurs de l'id de l'article est de l'id du commentaires sont passées au script par l'intermédiaire d'un champ caché
  <input type="hidden" name="id_comment" value="<?= $commentaire['id'] ?>">
            <p><label for="texte">Commentaire :</label></p>

Après éxecution de la requête (mise à jour) on actualise la page detail_article.php
header("Location: detail_article.php?id=$id_article");

Création de la page liste_commentaire.php
On écrit une requête pour récupérer tous les commentaires de l'utilisateur dont l'id est en variables de session

Pour chaque commentaire on ajoute un lien permettant d'accéder a l'article complet avec les tous les commentaires
 echo "<a href='detail_article.php?id={$commentaire['id_article']}'>Voir le détail de l'article</a>";

Refactorisation de la page dashboard.php
On place les boutons avec une structure alternantive pour se connecter et se déconnecter 
On implementes également les boutons de navigation pour accéder à la liste des articles et a la liste des commentaires
