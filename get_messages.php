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
        'texte' => $row['texte'],
        'heure' => date('H:i', strtotime($row['heure']))
    ];
}
echo json_encode($messages);
$stmt->close();
?>
