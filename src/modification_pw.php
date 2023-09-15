<?php
session_start();
$token = uniqid(rand(), true); //creation token unique 
$_SESSION['token'] = $token; //stockage
$_SESSION['token_time'] = time(); //stockage time stamp du token
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'Inscription</title>
    <!-- Inclure les styles Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Mon Site</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">            
            <li class="nav-item">
                <a class="nav-link" href="index.html">Retour</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container mt-5">
    <h2>Modification du mot de passe</h2>
    <?php
  
  //  vérifiez s'il y a des erreurs et affichez-les le cas échéant
    if (!empty($_SESSION["error_messages"])) {

        echo '<div class="alert alert-danger" role="alert">';
        foreach ($_SESSION["error_messages"] as $errorMessage) {
            echo $errorMessage . "<br>";
        }
            echo '</div>';

      // Une fois les messages affichés, supprimez-les de la session
        unset($_SESSION["error_messages"]);
}
$tocken = $_GET['token'];

    
?>


    <form action="traitement_modif_pw.php" method="POST">


        <!-- Champ : Mot de passe -->
        <div class="form-group">
            <label for="password">Nouveau mot de passe</label>
            <input type="password" class="form-control" id="password" name="user_changementPw" required>
        </div>

        <!-- Champ : Répéter le mot de passe -->
        <div class="form-group">
            <label for="password_repeat">Confirmer le mot de passe</label>
            <input type="password" class="form-control" id="password_repeat" name="user_changePwConfirm" required>
        </div>

        <!-- jeton caché dans formulaire -->
        <input type="hidden" name="token" id="token" value="<?php echo $token; ?>">
        <input type="hidden" name="tocken" id="tocken" value="<?php echo $tocken; ?>">
        
        <!-- Bouton d'envoi -->
        <button type="submit" class="btn btn-primary" name="btn_user_changePw">Changer le mot de passe</button>
    </form>
</div>
 
<!-- Inclure les scripts Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
