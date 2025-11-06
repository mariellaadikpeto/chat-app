<?php
session_start();
require_once "config.php";

header('Content-Type: application/json');

if (!isset($_SESSION['utilisateur_id']) || !isset($_GET['user_id'])) {
    echo json_encode([]);
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$destinataire_id = intval($_GET['user_id']);

// Vérifier que le destinataire existe
$stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $destinataire_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    echo json_encode([]);
    exit;
}
$stmt->close();

$sql = "SELECT * FROM message 
        WHERE (utilisateur_1 = ? AND utilisateur_2 = ?) 
           OR (utilisateur_1 = ? AND utilisateur_2 = ?)
        ORDER BY message_id ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $utilisateur_id, $destinataire_id, $destinataire_id, $utilisateur_id);
$stmt->execute();
$res = $stmt->get_result();
$messages = [];

while($row = $res->fetch_assoc()){
    $messages[] = [
        'message_id' => $row['message_id'],
        'utilisateur_id' => $row['utilisateur_1'],
        'texte' => htmlspecialchars($row['texte']),
        'heure' => date('H:i', strtotime($row['heure']))
    ];
}
echo json_encode($messages);
$stmt->close();
?>
