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
$errorMessages = [];
$message = '';

//si aucun token n'est spécifié en paramètre de l'url

if (empty($_POST['tocken'])){
    echo 'Aucun token n\'a été spécifié';
    exit;
}
 //on récupère les informatins par rapport au token dans la base de données
$query = $conn->prepare('SELECT date_demande_recuperation_pw FROM user WHERE pwd_recuperation_token = ?');
$query->bindValue(1, $_POST['tocken']);
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);
if (empty($row)){  //si aucun info associée au token n'est trouvé
    echo 'Ce token n\'a pas été trouvé';
    exit;
}
//on calcul la date de la génératin du token + 1 heure
$dateToken = strtotime('+1 hours', strtotime($row['date_demande_recuperation_pw']));
$dateToday = time();

if ($dateToken < $dateToday){  //si la date a dépassé le delais de 3heures
    echo 'Token expiré !';
    exit;
}

//si le formulaire a été soumis
if (isset($_POST['btn_user_changePw'])) {
    
    //si le formulaire est correctement remplit
    if(!empty($_POST['user_changementPw']) && !empty($_POST['user_changePwConfirm'])) {
       
        //si les deux mots de passes sont les mêmes
        if ($_POST['user_changementPw'] === $_POST['user_changePwConfirm']){
            //on hash le mot de passe
            $password = password_hash($_POST['user_changementPw'], PASSWORD_DEFAULT);
            //on modifie les information de la base données
            $req='UPDATE user set user_pw = ?, pwd_recuperation_token = "" WHERE pwd_recuperation_token = ?';
            $query = $conn->prepare($req);
//            echo $req." ********** ".$password." ******** ".$_GET['token'];
            $query->bindValue(1, $password);
            $query->bindValue(2, $_POST['tocken']);
            $query->execute();
           
            $errorMessages[] = 'Le mot de passe a été changé !';
            $_SESSION["error_messages"] = $errorMessages;
            header("Location: index.php");
            exit();
            //si les deux mot de passe ne sont pas identiques
        } else {
            
            $errorMessages[] = 'Les deux mots de passes ne sont pas identiques.';
            $_SESSION["error_messages"] = $errorMessages;
            header("Location: modification_pw.php");
            exit();
        }
    } else {
        
        $errorMessages[] = 'Veuillez remplir tous les champs du formulaire.';
        $_SESSION["error_messages"] = $errorMessages;
        header("Location: modification_pw.php");
        exit();
    }
}