<?php
session_start(); // Démarrage de la session

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

require("connexion_db.php");
$conn = connectDatabase();


    // Initialisation du tableau des messages d'erreur
    $errorMessages = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les données du formulaire
        $password = $_POST["password"];
        $email = $_POST["email"];

        // Vérifier si la case à cocher "Se souvenir de moi" a été cochée
        $rememberMe = isset($_POST['rememberMe']) ? true : false;

        // Vérifier si l'adresse e-mail et le mot de passe correspondent
        $stmt = $conn->prepare("SELECT * FROM user WHERE user_email = :email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();


       if ($user && password_verify($password, $user['user_pw'])) {
         
            $_SESSION['user_id'] = $user['iduser'];
            // Connexion réussie, vous pouvez rediriger l'utilisateur vers la page du tableau de bord
            
            header("Location: dashbord.php");
        
            exit();
        } else {
            // Adresse e-mail et/ou mot de passe incorrects, afficher un message d'erreur
            $errorMessages[] = "Adresse e-mail et/ou mot de passe incorrects.";
            $_SESSION["error_messages"] = $errorMessages;
            header("Location: index.php");
            exit();
        }
    } 

