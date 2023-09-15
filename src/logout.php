<?php

// Démarrer la session
session_start();

    if(isset($_SESSION['user_id'])){
        require ("connexion_db.php");
        $conn = connectDatabase();
        $userId = $_SESSION['user_id'];
        // Supprimez le token d'authentification de la base de données (vous devez implémenter cette fonction)
        deleteAuthToken($conn, $userId);
        // Détruisez la session pour déconnecter l'utilisateur
        session_destroy();
    }

    // Rediriger l'utilisateur vers la page d'accueil (index.php)
    header("Location: index.php");
    exit();


    function deleteAuthToken($conn, $userId)
    {
        $stmt = $conn->prepare("UPDATE user SET auth_token = NULL WHERE iduser = :id");
        $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
        $stmt->execute();
    }