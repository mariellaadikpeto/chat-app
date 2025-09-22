<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['utilisateur_id'])) exit;

$utilisateur_1 = $_SESSION['utilisateur_id'];
$utilisateur_2 = intval($_POST['destinataire_id']);
$texte = trim($_POST['message']);
$heure = date('Y-m-d H:i:s');

if ($texte !== '') {
    $sql = "INSERT INTO message (utilisateur_1, utilisateur_2, texte, heure) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $utilisateur_1, $utilisateur_2, $texte, $heure);
    $stmt->execute();
    $stmt->close();
}
?>
