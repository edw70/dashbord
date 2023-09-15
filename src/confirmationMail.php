<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Connexion</title>
    <!-- Inclure les styles Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="authentification.js" defer></script> 
    
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-transparent">
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
    <h2>Confirmation d'envoie d'email</h2>
    <?php

        // Si le formulaire a été soumis, vérifiez s'il y a des erreurs et affichez-les le cas échéant
        if (!empty($_SESSION["error_messages"])) {

            echo '<div class="alert alert-primary" role="alert">';
            foreach ($_SESSION["error_messages"] as $errorMessage) {
                echo $errorMessage . "<br>";
            }
                echo '</div>';

            // Une fois les messages affichés, supprimez-les de la session
            unset($_SESSION["error_messages"]);
            
        }
    ?>
    


</div>

<!-- Inclure les scripts Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

</body>
</html>
