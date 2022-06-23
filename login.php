<?php  session_start(); 
include_once('utils.php');

// Étape 1 : récupérer les données de formulaires : $_POST
$email = $_POST["email"]; 
$password = $_POST["password"];

// Étape 2 : connexion à la base 

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

// Étape 3 récupération de l'utilisateur (*) à partir de son email 

$rqt = "SELECT * FROM utilisateur WHERE email=:email"; 
try {

    $requete_preparee = $bdd->prepare($rqt); 
    $requete_preparee->bindParam(':email', $email); 
    $requete_preparee->execute(); 
    $user = $requete_preparee->fetch(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    // On affiche les erreurs relative à la BDD SEULEMENT EN DEV!!!!!!
    echo $e->getMessage();
    http_response_code(500);
    exit; 
}

if(empty($user)) {
    redirect_with_error("login_form.php", "invalid");
}

// Étape 4 : vérification du mot de passe 
$hash = $user["password"]; 

if(!password_verify($password, $hash)) {
    // 4.1 : si erreur on lui indique 
    redirect_with_error("login_form.php", "invalid");
}

    // 4.2 : si ok on l'envoi sur une page de succes (genre dashboard)
$_SESSION['profil']['id']=$user['id'];
$_SESSION['profil']['email']=$email;
// header('Location: dashboard.php');
echo $_SESSION['profil']['id'];
echo $_SESSION['profil']['email'];
