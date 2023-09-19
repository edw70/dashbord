<?php
//  méthode est POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer les données JSON envoyées depuis JavaScript
    $data = json_decode(file_get_contents("php://input"));

    // Vérifier si le token d'authentification est valide 
    $authToken = $data->authToken;
    // Déchiffrez le token pour vérification (partie ajouter)
   

    
    $isValid = verifyAuthToken($authToken);

    // Préparer la réponse au format JSON
    $response = array();

    if ($isValid) {
        $response["authenticated"] = true;
    } else {
        $response["authenticated"] = false;
    }

    // Envoyer la réponse au format JSON
    header("Content-Type: application/json");
    echo json_encode($response);
} else {
    // Si la méthode de la requête n'est pas POST, renvoyer une erreur
    header("HTTP/1.1 405 Method Not Allowed");
    echo "Méthode non autorisée.";
}

// Fonction de vérification du token d'authentification 
function verifyAuthToken($token) {
    $encryptionKey = '1234';
    $encryptionKeyIV = '1234567891234567';
    $userId = openssl_decrypt($token, 'aes-256-cbc', $encryptionKey, 0, $encryptionKeyIV );
    //connexion à la base de donnée
    require("connexion_db.php");
    $conn = connectDatabase();

    $query = "SELECT * FROM user WHERE auth_token = :token";
    $stmt = $conn->prepare($query);

    // Liez le paramètre token à la requête en utilisant un marqueur nommé
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);

    $stmt->execute();
    
    // Récupérez le résultat de la requête
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ( openssl_decrypt($result['auth_token'], 'aes-256-cbc', $encryptionKey, 0, $encryptionKeyIV ) === $userId ) {
        // Le token est valide
        $conn = null; // Fermez la connexion à la base de données
        return true;
    } else {
        // Le token n'a pas été trouvé dans la base de données
        $conn = null; // Fermez la connexion à la base de données
        return false;
    }
}

