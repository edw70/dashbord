<?php
//phpinfo();
session_start(); //demarrage session
//traitement du token csrf
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



//************gestion du formulaire ************************************ */


// Informations de connexion à la base de données
require("connexion_db.php");
$conn = connectDatabase();


    // Initialisation du tableau des messages d'erreur
    $errorMessages = [];
    $validationMessages = [];
    // Utiliser $conn pour exécuter des requêtes SQL 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les données du formulaire
        $prenom = $_POST["prenom"];
        $nom = $_POST["nom"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["password_repeat"];
        $email = $_POST["email"];
        // Vérifier si l'adresse e-mail est déjà utilisée
        $sql_check_email = "SELECT COUNT(*) FROM user WHERE user_email = :user_email";
        $stmt_check_email = $conn->prepare($sql_check_email);
        $stmt_check_email->bindParam(":user_email", $email, PDO::PARAM_STR);
        $stmt_check_email->execute();
        $email_exists = $stmt_check_email->fetchColumn();

        if($email_exists){
            // L'adresse e-mail est déjà utilisée, afficher un message d'erreur
            $errorMessages[] = "Cette adresse e-mail est déjà enregistrée.";
            $_SESSION["error_messages"] = $errorMessages;

            header("Location: signup.html");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessages[] = "Adresse email invalide";
        } elseif (!empty($password) && !empty($confirmPassword) && $password != $confirmPassword) {
            $errorMessages[] = "Les mots de passe ne correspondent pas";
        }

        if (!empty($errorMessages)) {
            // S'il y a des erreurs, stockez-les dans la session
            $_SESSION["error_messages"] = $errorMessages;
            $_SESSION["prenom"] = $_POST['prenom'];
            $_SESSION["nom"] = $_POST['nom'];
            $_SESSION["email"] = $_POST['email'];
            // Redirigez l'utilisateur vers signup.php
            header("Location: signup.html");
            exit();
        } else {
            // Hash du mot de passe
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Requête SQL pour insérer l'utilisateur dans la table "user"
            $sql = "INSERT INTO user (user_name, user_surname, user_email, user_pw) VALUES (:user_name, :user_surname, :user_email, :user_pw)";
            
            try {
                $stmt = $conn->prepare($sql); 
                $stmt->bindParam(":user_name", $prenom, PDO::PARAM_STR);
                $stmt->bindParam(":user_surname", $nom, PDO::PARAM_STR);
                $stmt->bindParam(":user_email", $email, PDO::PARAM_STR);
                $stmt->bindParam(":user_pw", $passwordHash, PDO::PARAM_STR);
                $stmt->execute();
                //echo "Inscription réussie ! veuillez vous connecter";
                $validationMessages [] = "Inscription réussie! veuillez vous connecter";
                $_SESSION["validationMessages"] = $validationMessages;
             //   session_unset();
                // Redirection de l'utilisateur 
                header("Location: http://php-dev-1.online/");
                exit();

            } catch (PDOException $e) {
                $errorMessages[] = "Erreur d'inscription : " . $e->getMessage();
                $_SESSION["error_messages"] = $errorMessages;
            }
        }
    }









