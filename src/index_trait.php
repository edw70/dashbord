<?php
//phpinfo();
session_start(); //demarrage session
$token = uniqid(rand(), true); //creation token unique 
$_SESSION['token'] = $token; //stockage
$_SESSION['token_time'] = time(); //stockage time stamp du token

// Démarrer la session
// verifier la presence du jeton dans la session et ds le formulaire
if(isset($_SESSION['token']) && isset($_SESSION['token_time']) && isset($_POST['token'])){
    //si le jeton session == jeton formulaire
    if($_SESSION['token'] === $_POST['token']){
        //verifier que le jeton n'est pas expiré
        $timestamp_ancien = time() - (15*60);
        if($_SESSION['token_time'] >= $timestamp_ancien){
        
            if(isset($_POST['message']) && !empty($_POST['message'])){
                $response = $_POST['message'];
                echo json_encode($response);
            }
        }
    }
    
}else{
    echo "error: invalid token";
}
  //  var_dump($_SESSION['token']); // Affiche la valeur du jeton en session
 //   var_dump($_POST['token']);     // Affiche la valeur du jeton soumis dans le formulaire




 // Informations de connexion à la base de données
 $host = 'mysql'; // Nom de l'hôte MySQL 
 $database = 'php-contact-db'; // Nom de la base de données MySQL
 $username = 'php-contact-user'; // Nom d'utilisateur MySQL
 $password = 'php-contact-pw'; // Mot de passe MySQL

try {
   // Chaîne de connexion PDO
    $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);

   // Configurer PDO pour rapporter les erreurs SQL
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connecté avec succès";


     // Utiliser $pdo pour exécuter des requêtes SQL 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
         // Récupérer les données du formulaire
        $prenom = $_POST["prenom"];
        $nom = $_POST["nom"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Crypter le mot de passe
        $email = $_POST["email"];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Adresse email invalide";
        } else {
             // Requête SQL pour insérer l'utilisateur dans la table "user"
            $sql = "INSERT INTO user (user_name, user_surname, user_email, user_pw) VALUES (:user_name, :user_surname, :user_email, :user_pw)";

            try {
                $stmt = $conn->prepare($sql); 
                $stmt->bindParam(":user_name", $prenom, PDO::PARAM_STR);
                $stmt->bindParam(":user_surname", $nom, PDO::PARAM_STR);
                $stmt->bindParam(":user_email", $email, PDO::PARAM_STR);
                $stmt->bindParam(":user_pw", $password, PDO::PARAM_STR);
                $stmt->execute();
                echo "Inscription réussie !";
            } catch (PDOException $e) {
                echo "Erreur d'inscription : " . $e->getMessage();
            }
        }
    }
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
// Redirection de l'utilisateur
header("Location: index.php");
exit();

?>








