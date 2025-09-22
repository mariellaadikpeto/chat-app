<?php
session_start();
require_once "config.php";

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: index.php");
    exit;
}

// Récupérer le terme de recherche s'il existe
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Requête pour récupérer les utilisateurs sauf l'utilisateur connecté
if ($search !== '') {
    $likeSearch = "%" . $search . "%"; // recherche partielle

    $sql = "SELECT id, nom, prenom, photo 
            FROM utilisateurs 
            WHERE id != ? AND (nom LIKE ? OR prenom LIKE ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) die("Erreur préparation requête : " . $conn->error);

    $stmt->bind_param("iss", $_SESSION['utilisateur_id'], $likeSearch, $likeSearch);
} else {
    $sql = "SELECT id, nom, prenom, photo FROM utilisateurs WHERE id != ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) die("Erreur préparation requête : " . $conn->error);

    $stmt->bind_param("i", $_SESSION['utilisateur_id']);
}

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
    <title>Rechercher utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f2f2;
        }
        .users-container {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .search-bar {
            display: flex;
            margin-bottom: 15px;
        }
        .search-bar input {
            flex: 1;
            border-radius: 20px 0 0 20px;
            padding: 5px 15px;
            border: 1px solid #ccc;
            outline: none;
        }
        .search-bar button {
            border-radius: 0 20px 20px 0;
            border: 1px solid #007bff;
            background-color: #007bff;
            color: white;
            padding: 5px 15px;
        }
        .user-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-decoration: none;
            color: inherit;
            transition: background 0.2s, transform 0.1s;
            border-radius: 8px;
        }
        .user-item:hover {
            background-color: #f0f8ff;
            transform: translateY(-2px);
        }
        .user-item img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #007bff;
        }
        .user-item h5 {
            margin: 0;
        }
        .btn-back {
            margin-top: 20px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="users-container">
        <h3 class="mb-3 text-center">Rechercher utilisateurs</h3>

        <!-- Barre de recherche -->
        <form class="search-bar" method="GET" action="rechercher.php">
            <input type="text" name="search" placeholder="Nom ou prénom..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Rechercher</button>
        </form>

        <!-- Liste des utilisateurs -->
        <?php if (!empty($utilisateurs)): ?>
            <?php foreach ($utilisateurs as $user): ?>
                <a href="chat.php?user_id=<?= $user['id'] ?>" class="user-item">
                    <?php if ($user['photo']): ?>
                        <img src="uploads/<?= htmlspecialchars($user['photo']) ?>" alt="Photo de profil">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/50" alt="Photo de profil">
                    <?php endif; ?>
                    <h5><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h5>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Aucun utilisateur trouvé.</p>
        <?php endif; ?>

        <a href="accueil.php" class="btn btn-secondary btn-back">Retour à l'accueil</a>
    </div>
</body>
</html>
