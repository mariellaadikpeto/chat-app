<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

    <div class="w-400 p-5 shadow rounded bg-white">
        <form method="POST" action="traitement_inscription.php" enctype="multipart/form-data">
            <div class="text-center mb-4">
                <img src="https://images.seeklogo.com/logo-png/39/1/chatcoin-chat-logo-png_seeklogo-399650.png" alt="logo" height="100px">
                <h3 class="mt-3">INSCRIPTION</h3>
            </div>

            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input name="nom" type="text" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Prénom</label>
                <input name="prenom" type="text" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input name="password" type="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Photo de profil</label>
                <input name="photo" type="file" class="form-control" accept="image/*">
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-success">S'inscrire</button>
                <a href="index.php">Connexion</a>
            </div>
        </form>
    </div>

</body>
</html>
