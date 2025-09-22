<?php
session_start();
require_once "config.php";

// Vérifier connexion
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: index.php");
    exit;
}

// Id de l'utilisateur avec qui on discute
$destinataire_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Info utilisateur connecté
$utilisateur_id = $_SESSION['utilisateur_id'];
$nom = $_SESSION['utilisateur_nom'];
$prenom = $_SESSION['utilisateur_prenom'];
$photo = $_SESSION['utilisateur_photo'];

// Info destinataire
$stmt = $conn->prepare("SELECT nom, prenom, photo FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $destinataire_id);
$stmt->execute();
$res = $stmt->get_result();
$destinataire = $res->fetch_assoc();
$stmt->close();

if (!$destinataire) {
    echo "Utilisateur introuvable.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Chat avec <?= htmlspecialchars($destinataire['prenom'] . ' ' . $destinataire['nom']); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #e5ddd5; font-family: Arial, sans-serif; }
.chat-container {
    max-width: 500px;
    margin: 50px auto;
    background: #fff;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    height: 600px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    overflow: hidden;
}
.chat-header {
    background: #007bff;
    color: #fff;
    padding: 10px 15px;
    font-weight: 500;
    text-align: center;
}
.messages {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
    background: #e5ddd5;
}
.message {
    display: flex;
    margin-bottom: 10px;
    align-items: flex-end;
}
.message.me { justify-content: flex-end; }
.message.you { justify-content: flex-start; }
.bubble {
    max-width: 70%;
    padding: 10px 15px;
    border-radius: 20px;
    position: relative;
    word-wrap: break-word;
}
.bubble.me {
    background: #dcf8c6;
    color: #000;
    border-bottom-right-radius: 0;
}
.bubble.you {
    background: #fff;
    color: #000;
    border-bottom-left-radius: 0;
}
.bubble .time {
    font-size: 0.7rem;
    color: #555;
    display: block;
    text-align: right;
    margin-top: 5px;
}
.chat-input {
    display: flex;
    padding: 10px;
    border-top: 1px solid #ccc;
    background: #f0f0f0;
}
.chat-input input {
    flex: 1;
    border-radius: 20px;
    border: 1px solid #ccc;
    padding: 10px 15px;
    outline: none;
}
.chat-input button {
    border-radius: 50%;
    border: none;
    background: #007bff;
    color: #fff;
    width: 45px;
    height: 45px;
    margin-left: 10px;
    cursor: pointer;
}
</style>
</head>
<body>
<div class="chat-container">
    <div class="chat-header">
        Chat avec <?= htmlspecialchars($destinataire['prenom'] . ' ' . $destinataire['nom']); ?>
    </div>

    <div class="messages" id="messages"></div>

    <form class="chat-input" id="chat-form">
        <input type="text" id="message" placeholder="Écrire un message..." autocomplete="off" required>
        <button type="submit">➤</button>
    </form>

    <a href="utilisateurs.php" class="btn btn-secondary mt-2 w-100">Retour aux utilisateurs</a>
</div>

<script>
const chatForm = document.getElementById('chat-form');
const messagesDiv = document.getElementById('messages');
const destinataire_id = <?= $destinataire_id ?>;
const utilisateur_id = <?= $utilisateur_id ?>;

// Charger les messages
function loadMessages() {
    fetch('get_messages.php?user_id=' + destinataire_id)
    .then(res => res.json())
    .then(data => {
        messagesDiv.innerHTML = '';
        data.forEach(msg => {
            const div = document.createElement('div');
            div.classList.add('message', msg.utilisateur_id == utilisateur_id ? 'me' : 'you');
            div.innerHTML = `<div class="bubble ${msg.utilisateur_id == utilisateur_id ? 'me' : 'you'}">
                                ${msg.texte}<span class="time">${msg.heure}</span>
                             </div>`;
            messagesDiv.appendChild(div);
        });
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    });
}

// Envoyer un message
chatForm.addEventListener('submit', e => {
    e.preventDefault();
    const texte = document.getElementById('message').value.trim();
    if (!texte) return;

    fetch('send_message.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'destinataire_id=' + destinataire_id + '&message=' + encodeURIComponent(texte)
    }).then(() => {
        document.getElementById('message').value = '';
        loadMessages();
    });
});

// Rafraîchissement toutes les 2 secondes
setInterval(loadMessages, 2000);
loadMessages();
</script>
</body>
</html>
