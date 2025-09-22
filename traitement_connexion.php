<?php
// traitement_connexion.php
// Aucun echo / rien avant <?php
session_start();
require_once "config.php"; // doit définir $conn (mysqli)

// DEBUG = true pour écrire des logs (mettre false en production)
$DEBUG = false;
if ($DEBUG) error_log("[AUTH] start traitement_connexion");

// variable d'erreur unique
$error = null;

// 1) méthode POST ?
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $error = "Accès non autorisé.";
}

// 2) récupération des champs
if (!$error) {
    $nom      = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($nom === '' || $password === '') {
        $error = "Tous les champs sont obligatoires.";
    }
}

if ($DEBUG) error_log("[AUTH] nom='$nom'");

// 3) si pas d'erreur, récupérer l'utilisateur
$user = null;
if (!$error) {
    $sql = "SELECT id, nom, prenom, mot_de_passe, photo
            FROM utilisateurs
            WHERE LOWER(nom) = LOWER(?)
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("[AUTH] prepare error: " . $conn->error);
        $error = "Erreur serveur. Réessaye plus tard.";
    } else {
        $stmt->bind_param("s", $nom);
        if (!$stmt->execute()) {
            error_log("[AUTH] execute error: " . $stmt->error);
            $error = "Erreur serveur. Réessaye plus tard.";
        } else {
            // fallback si get_result non disponible
            if (method_exists($stmt, 'get_result')) {
                $res = $stmt->get_result();
                if ($res && $res->num_rows === 1) {
                    $user = $res->fetch_assoc();
                }
            } else {
                // bind_result fallback
                $stmt->bind_result($id, $db_nom, $db_prenom, $db_mot_de_passe, $db_photo);
                if ($stmt->fetch()) {
                    $user = [
                        'id' => $id,
                        'nom' => $db_nom,
                        'prenom' => $db_prenom,
                        'mot_de_passe' => $db_mot_de_passe,
                        'photo' => $db_photo
                    ];
                }
            }
        }
        $stmt->close();
    }
}

if ($DEBUG) error_log("[AUTH] user found? " . ($user ? 'yes' : 'no'));

// 4) vérifier l'utilisateur et le mot de passe
if (!$error) {
    if (!$user) {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    } else {
        $hash = $user['mot_de_passe'] ?? '';
        $motDePasseOK = false;

        // Si mot de passe haché
        if ($hash !== '' && password_verify($password, $hash)) {
            $motDePasseOK = true;
        } else {
            // fallback (temporaire) : mot de passe en clair dans la BDD (à supprimer après migration)
            if ($password === $hash) {
                $motDePasseOK = true;
            }
        }

        if (!$motDePasseOK) {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
}

// 5) si erreur -> retour vers index avec message; sinon créer session et rediriger
if ($error) {
    $_SESSION['error'] = $error;
    if ($DEBUG) error_log("[AUTH] error -> redirect to index: $error");
    header("Location: index.php");
    exit;
}

// Succès : remplir la session
$_SESSION['utilisateur_id']     = $user['id'];
$_SESSION['utilisateur_nom']    = $user['nom'];
$_SESSION['utilisateur_prenom'] = $user['prenom'];
$_SESSION['utilisateur_photo']  = $user['photo'] ?? null;

// Message de succès affichable sur accueil.php
$_SESSION['success'] = "Connexion réussie. Bienvenue, " . htmlspecialchars($user['prenom']) . " !";

if ($DEBUG) error_log("[AUTH] success -> redirect to accueil for user id=" . $user['id']);
header("Location: accueil.php");
exit;
