<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: index.php");
    exit;
}

// Récupérer tous les utilisateurs sauf l'utilisateur connecté
$sql = "SELECT id, nom, prenom, photo FROM utilisateurs WHERE id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['utilisateur_id']);
$stmt->execute();
$res = $stmt->get_result();
$utilisateurs = [];
while ($row = $res->fetch_assoc()) {
    $utilisateurs[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f2f2f2; }
        .users-container { max-width: 600px; margin: 50px auto; background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.2); }
        .user-item { display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #ddd; transition: background 0.2s; }
        .user-item:hover { background-color: #f0f8ff; }
        .user-info { display: flex; align-items: center; gap: 15px; text-decoration: none; color: inherit; }
        .user-info img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #007bff; }
        .user-info h5 { margin: 0; }
        .btn-discuter { background-color: #007bff; color: #fff; border: none; padding: 5px 12px; border-radius: 8px; text-decoration: none; }
        .btn-discuter:hover { background-color: #0056b3; color: #fff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="users-container">
        <h3 class="mb-4 text-center">Utilisateurs inscrits</h3>

        <?php if (!empty($utilisateurs)): ?>
            <?php foreach ($utilisateurs as $user): ?>
                <div class="user-item">
                    <a href="chat.php?user_id=<?= $user['id'] ?>" class="user-info">
                        <?php if ($user['photo']): ?>
                            <img src="uploads/<?= htmlspecialchars($user['photo']); ?>" alt="Photo de profil">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/50" alt="Photo de profil">
                        <?php endif; ?>
                        <h5><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h5>
                    </a>
                    <a href="chat.php?user_id=<?= $user['id'] ?>" class="btn-discuter">Discuter</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Aucun autre utilisateur inscrit.</p>
        <?php endif; ?>

        <a href="accueil.php" class="btn btn-secondary mt-3 w-100">Retour à l'accueil</a>
    </div>
</body>
</html>
