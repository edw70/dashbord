<?php
function connectDatabase(){
    try {

        // Récupérer les variables d'environnement avec getenv
        $mysqlRootPassword = getenv('MYSQL_ROOT_PASSWORD');
        $mysqlDatabase = getenv('MYSQL_DATABASE');
        $mysqlUser = getenv('MYSQL_USER');
        $mysqlPassword = getenv('MYSQL_PASSWORD');
        
        // Configurer la connexion à la base de données
        $host = 'mysql'; // Nom de l'hôte MySQL
        // Chaîne de connexion PDO
        $conn = new PDO("mysql:host=$host;dbname=$mysqlDatabase;charset=utf8", $mysqlUser, $mysqlPassword);
        
        // Configuration de PDO pour rapporter les erreurs SQL
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e){

       // die("Erreur de connexion à la base de données :" . $e->getMessage());
        echo"Erreur de connexion à la base de données :" . $e->getMessage();
    }
}
