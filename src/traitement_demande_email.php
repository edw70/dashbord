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
//connexion base de donnée
require("connexion_db.php");
$conn = connectDatabase();

// Initialisation du tableau des messages d'erreur
$errorMessages = [];
$validationMessages = [];
$message='';
//si le formulaire a été soumis
if(isset($_POST['btn_user_reset'])) {
    //si le formulaire est correctement remplit
    if(!empty($_POST['user_email'])){
    //requete pour verifier si l'email est associé à un compte
    $stmt = $conn->prepare('SELECT COUNT(*) AS nb FROM user WHERE user_email = ?');
    $stmt->bindValue(1, $_POST['user_email']);
    $stmt->execute();

    $ligne = $stmt->fetch(PDO::FETCH_ASSOC);
    //tester si l'adresse email est associé à un compte
    if (!empty($ligne) && $ligne['nb'] > 0) {
        //on génère notre token
        $string = implode('', array_merge(range('A','Z'), range('a','z'), range('0','9')));
        $token = substr(str_shuffle($string), 0, 20);

        //on insère la date et le token dans la db
        $stmt = $conn->prepare('UPDATE user SET date_demande_recuperation_pw =
        NOW(), pwd_recuperation_token = ? WHERE user_email = ?');
        $stmt->bindValue(1, $token);
        $stmt->bindValue(2, $_POST['user_email']);
        $stmt->execute();

        //on prepare l'envoie du courriel
        $link = 'http://php-dev-1.online/modification_pw.html?token='.$token;
//        $link = 'chemin?token='.$token;
        $to = $_POST['user_email'];
        $subject = 'Réinitialisation de votre mot de passe';
        $message = '<h1>Réinitialisation de votre mot de passe</h1><p>Pour réinitialiser votre mot de passe, veuillez suivre le lien : <a href="'.$link.'">'.$link.'</a></p>';
        //on défini les entêtes requis

        $header = "MIME-Version: 1.0" . "\r\n";
        $header .= "Content-type: text/html; charset=utf-8" . "\r\n";
        $header .= 'To: '.$to.' <'.$to.'>' . "\r\n";
        $header .= 'From: info@mescontacts.com';
        //on envoie le courriel
        mail($to, $subject, $message, $header);
        $validationMessages [] = "Un courriel a été acheminé.
        Veuillez regarder votre boite de réception mail et suivre les instructions.";
        $_SESSION["validationMessages"] = $validationMessages;
        header("Location: confirmationMail.html");
        exit();
    }else{ //si email pas associé à un compte
        $errorMessages[] = "Adresse email non enregistré.";
        $_SESSION["error_messages"] = $errorMessages;
        header("Location: demande_email.html");
        exit();
    }
    }else { //si le formulaire n'est pas correctement remplit
        $errorMessages[] = "Veuillez spécifier une adresse courriel.";
        header("Location: demande_email.html");
        exit();
    }
    
}
