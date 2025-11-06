<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['utilisateur_id'])) exit;

$utilisateur_1 = $_SESSION['utilisateur_id'];
$utilisateur_2 = intval($_POST['destinataire_id']);
$texte = trim($_POST['message']);
$heure = date('Y-m-d H:i:s');

// Vérifier que le destinataire existe
$stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $utilisateur_2);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    exit; // Destinataire inexistant
}
$stmt->close();

// Vérifier que le texte n'est pas vide et pas trop long (max 500 caractères)
if ($texte === '' || strlen($texte) > 500) {
    exit; 
}

// Insérer le message
$sql = "INSERT INTO message (utilisateur_1, utilisateur_2, texte, heure) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $utilisateur_1, $utilisateur_2, $texte, $heure);
$stmt->execute();
$stmt->close();
?>
