<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: index.php");
    exit;
}

$nom = $_SESSION['utilisateur_nom'];
$prenom = $_SESSION['utilisateur_prenom'];
$photo = $_SESSION['utilisateur_photo'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f2f2f2; }
        .container { max-width: 400px; margin: 100px auto; background: #fff; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.2); }
        .profile img { width: 80px; height: 80px; border-radius: 50%; border: 2px solid #007bff; object-fit: cover; }
        .btn-list { margin-top: 20px; width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile">
            <?php if ($photo): ?>
                <img src="uploads/<?= htmlspecialchars($photo); ?>" alt="Photo de profil">
            <?php endif; ?>
            <h3><?= htmlspecialchars($prenom . ' ' . $nom); ?></h3>
        </div>

        <a href="utilisateurs.php" class="btn btn-primary btn-list">Voir la liste des utilisateurs</a>
        <a href="logout.php" class="btn btn-danger btn-list">Se déconnecter</a>
    </div>
</body>
</html>
