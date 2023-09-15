<?php
session_start();

if (!isset($_SESSION['user_id'])){

    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gestion des Contacts</title>
    <!-- Inclure les styles Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="deconnexion.js" defer></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.html">Mon Tableau de Bord</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">            
            <li class="nav-item">
                <a class="nav-link" id="deconnecter" href="index.php">Se déconnecter</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Tableau de Bord - Gestion des Contacts</h2>
    <p>Bienvenue dans votre tableau de bord de gestion des contacts. Vous pouvez ajouter, modifier ou supprimer des contacts ici.</p>
    <div class="row">
        <div class="col-md-6">
            <!-- Formulaire d'ajout de contact -->
            <form action="traitement_dashbord.php" method="POST">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                </div>
                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}" title="Exemple d'adresse email : email@example.com" >
                </div>
    <?php
  
        // Si le formulaire a été soumis, vérifiez s'il y a des erreurs et affichez-les le cas échéant
        if (!empty($_SESSION["error_messages"])) {
            echo '<p style="color: red;">';
            foreach ($_SESSION["error_messages"] as $errorMessage) {
                echo $errorMessage . "<br>";
            }
            echo '</p>';
            // Une fois les messages affichés, supprimez-les de la session
            unset($_SESSION["error_messages"]);
            
        }
   
    ?>
                <button type="submit" class="btn btn-primary">Ajouter Contact</button>
            </form>
        </div>
        <div class="col-md-6">
            <!-- Liste des contacts -->
            <h3>Liste des Contacts</h3>
            <ul class="list-group">
            <?php
require("connexion_db.php");
$conn = connectDatabase();

$sql_select_contacts = "SELECT c.contact_name, c.contact_surname, c.contact_email 
                        FROM contact c 
                        JOIN user_contact uc ON c.idcontact = uc.contact_idcontact  
                        WHERE uc.user_iduser = :user_id";
$stmt_select_contacts = $conn->prepare($sql_select_contacts);
$stmt_select_contacts->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmt_select_contacts->execute();

while ($row = $stmt_select_contacts->fetch(PDO::FETCH_ASSOC)){
    $contact_name = $row['contact_name'];
    $contact_surname = $row['contact_surname'];
    $contact_email = $row['contact_email'];
    echo '<li class="list-group-item list-group-item-info">' . $contact_name . ' ' . $contact_surname.'  ' . '-' .'  ' . $contact_email . '</li>';
}
?>

                <li class="list-group-item">John Doe - john.doe@example.com</li>
                <li class="list-group-item">Jane Smith - jane.smith@example.com</li>
                <li class="list-group-item">Michael Johnson - michael.johnson@example.com</li>
            </ul>
        </div>
    </div>
</div>

<!-- Inclure les scripts Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

</body>
</html>
