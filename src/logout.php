<?php
// Démarrer la session
session_start();

if(isset($_SESSION['user_id'])){
    require ("connexion_db.php");
    $conn = connectDatabase();
    $userId = $_SESSION['user_id'];
    // Supprimez le token d'authentification de la base de données 
    deleteAuthToken($conn, $userId);
    // Détruisez la session pour déconnecter l'utilisateur
    session_destroy();
    echo '<script>
            localStorage.removeItem("authToken");
            window.location.href = "http://php-dev-1.online";
        </script>';

}

// Rediriger l'utilisateur vers la page d'accueil (index.php)
 //   header("Location: index.html");
//    exit();

    

    function deleteAuthToken($conn, $userId)
    {
        $stmt = $conn->prepare("UPDATE user SET auth_token = NULL WHERE iduser = :id");
        $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
        $stmt->execute();
    }