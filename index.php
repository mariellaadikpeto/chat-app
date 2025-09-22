<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

    <div class="w-400 p-5 shadow rounded bg-white">

        <!-- Affichage des messages -->
        <?php
        if (!empty($_SESSION['error'])) {
            echo '<div class="alert alert-danger text-center" role="alert">'
                 .htmlspecialchars($_SESSION['error']).'</div>';
            unset($_SESSION['error']);
        }
        if (!empty($_SESSION['success'])) {
            echo '<div class="alert alert-success text-center" role="alert">'
                 .htmlspecialchars($_SESSION['success']).'</div>';
            unset($_SESSION['success']);
        }
        ?>

        <form method="POST" action="traitement_connexion.php">
            <div class="text-center mb-4">
                <img src="https://images.seeklogo.com/logo-png/39/1/chatcoin-chat-logo-png_seeklogo-399650.png" alt="logo" height="100px">
                <h3 class="mt-3">CONNEXION</h3>
            </div>

            <div class="mb-3">
                <label class="form-label">Nom d'utilisateur</label>
                <input name="nom" type="text" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input name="password" type="password" class="form-control" required>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary">Se connecter</button>
                <a href="inscription.php">S'inscrire</a>
            </div>
        </form>
    </div>

</body>
</html>
