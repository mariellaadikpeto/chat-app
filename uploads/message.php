<?php
// message.php

// Récupérer toutes les conversations d'un utilisateur
function getConversation($utilisateur_id, $conn) {
    $sql = "SELECT * FROM message 
            WHERE utilisateur_1 = ? OR utilisateur_2 = ? 
            ORDER BY message_id DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("ii", $utilisateur_id, $utilisateur_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $messages = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $messages[] = $row;
        }
    }
    $stmt->close();
    return $messages;
}

// Envoyer un message
function sendMessage($utilisateur_1, $utilisateur_2, $contenu, $conn) {
    $sql = "INSERT INTO message (utilisateur_1, utilisateur_2, contenu, date_envoi) 
            VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("iis", $utilisateur_1, $utilisateur_2, $contenu);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
?>
