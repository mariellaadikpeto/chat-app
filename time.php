<?php
date_default_timezone_set('Africa/Porto-Novo'); // Remplace par ton fuseau horaire

// Si l'heure actuelle côté serveur
echo date('H:i'); // Affiche l'heure au format HH:MM

// Si tu veux envoyer l'heure d'un message spécifique depuis la BDD
/*
session_start();
require_once "config.php";

$message_id = isset($_GET['message_id']) ? intval($_GET['message_id']) : 0;

$sql = "SELECT heure FROM message WHERE message_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $message_id);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
    echo date('H:i', strtotime($row['heure']));
} else {
    echo "";
}
$stmt->close();
*/
?>
