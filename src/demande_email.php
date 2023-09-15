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
    <title>Formulaire de Connexion</title>
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
    <h2>Formulaire de Récupération</h2>
    <?php

        // Si le formulaire a été soumis, vérifiez s'il y a des erreurs et affichez-les le cas échéant
        if (!empty($_SESSION["error_messages"])) {

            echo '<div class="alert alert-danger" role="alert">';
            foreach ($_SESSION["error_messages"] as $errorMessage) {
                echo $errorMessage . "<br>";
            }
                echo '</div>';

            // Une fois les messages affichés, supprimez-les de la session
            unset($_SESSION["error_messages"]);
            
        }
    ?>
    
    <form action="traitement_demande_email.php" method="POST" id="demande_mail">
        <!-- Champ : Adresse e-mail -->
        <div class="form-group">
            <label for="email">Votre adresse e-mail</label>
            <input type="email" class="form-control" id="resetemail" name="user_email" required pattern="^[a-zA-Z0-9._%\\+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,4}$" title="Exemple d'adresse email : email@example.com">
        </div>

        <!-- jeton caché dans formulaire -->
        <input type="hidden" name="token" id="token" value="<?php echo $token; ?>">
        
        <!-- Bouton d'envoi -->
        <button type="submit" class="btn btn-primary" name="btn_user_reset">Réinitialiser le mot de passe</button>
    </form>
</div>               
        

<!-- Inclure les scripts Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

</body>
</html>
