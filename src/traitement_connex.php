<?php
session_start(); // Démarrage de la session

// Vérification de la présence du jeton dans la session et dans le formulaire
if (isset($_SESSION['token']) && isset($_SESSION['token_time']) && isset($_POST['token'])) {
    // Si le jeton de session est identique au jeton du formulaire
    if ($_SESSION['token'] === $_POST['token']) {
        // Vérifier que le jeton n'est pas expiré (15 minutes de validité)
        $timestamp_ancien = time() - (15 * 60);
        if ($_SESSION['token_time'] >= $timestamp_ancien) {

            if (isset($_POST['message']) && !empty($_POST['message'])) {
                $response = $_POST['message'];
                echo json_encode($response);
            }
        }
    }
} else {
    echo "Erreur : jeton invalide";
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

        // Renouvellement du jeton si "Se souvenir de moi" est coché
        if ($rememberMe) {
 //           
            // Générez une clé de chiffrement aléatoire 
            $encryptionKey = '1234';
            $encryptionKeyIV = '1234567891234567';

            // Chiffrement du jeton avec OpenSSL
            
            $newAuthToken = openssl_encrypt($user['iduser'], 'aes-256-cbc', $encryptionKey, 0, $encryptionKeyIV );
            
            // Stockez le nouveau jeton dans la base de données pour l'utilisateur
            updateAuthToken($conn, $user['iduser'], $newAuthToken);
            
            // Définissez une nouvelle expiration pour le jeton 
            $_SESSION['token_time'] = time() + (7 * 24 * 60 * 60); // 7 jours

            // Réinjectez le jeton d'authentification dans la session
            if (isset($_POST['authToken'])) {
                $_SESSION['authToken'] = $_POST['authToken'];
            }

            // Stockez également le jeton dans le local storage
            echo '<script>localStorage.setItem("authToken", "' . $newAuthToken . '"); window.location.href = "http://php-dev-1.online/dashbord.html";</script>';
            


        }else{// Connexion réussie, vous pouvez rediriger l'utilisateur vers la page du tableau de bord
            header("Location: dashbord.html");
        }

   } else {
        // Adresse e-mail et/ou mot de passe incorrects, afficher un message d'erreur
        $errorMessages[] = "Adresse e-mail et/ou mot de passe incorrects.";
        $_SESSION["error_messages"] = $errorMessages;
        header("Location: index.php");
        exit();
    }
} 

// Fonction pour mettre à jour le jeton d'authentification dans la base de données
function updateAuthToken($conn, $userId, $newAuthToken)
{
    $stmt = $conn->prepare("UPDATE user SET auth_token = :token WHERE iduser = :id");
    $stmt->bindParam(":token", $newAuthToken, PDO::PARAM_STR);
    $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
    $stmt->execute();
}

