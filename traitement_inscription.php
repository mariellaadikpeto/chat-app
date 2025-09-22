<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $password = trim($_POST["password"]);

    if (!empty($nom) && !empty($prenom) && !empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $photoName = null;
        if (!empty($_FILES["photo"]["name"])) {
            $photoName = time() . "_" . basename($_FILES["photo"]["name"]);
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            move_uploaded_file($_FILES["photo"]["tmp_name"], $targetDir . $photoName);
        }

        $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, prenom, mot_de_passe, photo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nom, $prenom, $passwordHash, $photoName);

        if ($stmt->execute()) {
            $_SESSION["success"] = "Inscription réussie ! Vous pouvez vous connecter.";
            header("Location: index.php");
            exit;
        } else {
            die("Erreur lors de l'inscription : " . $conn->error);
        }
        $stmt->close();
    } else {
        die("Tous les champs sont obligatoires.");
    }
} else {
    die("Accès non autorisé.");
}
?>
