<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "chat_app";

// Connexion à la base de données
$conn = new mysqli($host, $user, $pass, $db);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

// Encodage UTF-8
$conn->set_charset("utf8mb4");
?>
