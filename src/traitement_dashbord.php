<?php
session_start();
require("connexion_db.php");
$conn = connectDatabase();

    // Initialisation du tableau des messages d'erreur
    $errorMessages = [];

    // Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
    header("Location: index.php");
    exit();
}

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les données du formulaire
        $prenom = $_POST["prenom"];
        $nom = $_POST["nom"];
        $email = $_POST["email"];

        // Récupérer l'ID de l'utilisateur à partir de la session
        $user_id = $_SESSION['user_id'];

        // Vérifier si l'adresse e-mail du contact existe déjà
        $sql_check_email = "SELECT idcontact FROM contact WHERE contact_email = :contact_email";
        $stmt_check_mail = $conn->prepare($sql_check_email);
        $stmt_check_mail->bindParam(":contact_email", $email, PDO::PARAM_STR);
        $stmt_check_mail->execute();
        $email_exists = $stmt_check_mail->fetchColumn();

        if ($email_exists) {
            // Cette adresse e-mail de contact existe déjà
            $errorMessages[] = "Cette adresse e-mail de contact existe déjà.";
        } else {
            // Adresse e-mail du contact unique, insérer le contact dans la base de données
            $sql_insert_contact = "INSERT INTO contact (contact_name, contact_surname, contact_email) VALUES (:contact_name, :contact_surname, :contact_email)";
            $stmt_insert_contact = $conn->prepare($sql_insert_contact);
            $stmt_insert_contact->bindParam(":contact_name", $prenom, PDO::PARAM_STR);
            $stmt_insert_contact->bindParam(":contact_surname", $nom, PDO::PARAM_STR);
            $stmt_insert_contact->bindParam(":contact_email", $email, PDO::PARAM_STR);

            if ($stmt_insert_contact->execute()) {
                // Récupérez l'ID du contact nouvellement inséré
                $contact_id = $conn->lastInsertId();

                // Ensuite, liez ce contact à l'utilisateur dans la table "user_contact"
                $sql_insert_user_contact = "INSERT INTO user_contact (user_iduser, contact_idcontact) VALUES (:user_id, :contact_id)";
                $stmt_insert_user_contact = $conn->prepare($sql_insert_user_contact);
                $stmt_insert_user_contact->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                $stmt_insert_user_contact->bindParam(":contact_id", $contact_id, PDO::PARAM_INT);

                if ($stmt_insert_user_contact->execute()) {
                    // Contact ajouté avec succès, rediriger l'utilisateur vers le tableau de bord
                    $_SESSION['user_id'] = $user_id;
                    header("Location: dashbord.php");
                    exit();
                } else {
                    // Erreur lors de la liaison du contact à l'utilisateur
                    $errorMessages[] = "Erreur lors de la liaison du contact à l'utilisateur.";
                }
            } else {
                // Erreur lors de l'ajout du contact
                $errorMessages[] = "Erreur lors de l'ajout du contact.";
            }
        }
    }


// Si des erreurs se sont produites, stockez-les dans la session et redirigez l'utilisateur.
if (!empty($errorMessages)) {
    $_SESSION["error_messages"] = $errorMessages;
    header("Location: dashbord.php");
    exit();
}


