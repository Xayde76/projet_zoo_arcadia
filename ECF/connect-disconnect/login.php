<?php
require '../config.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_var($_POST['username'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$username) {
        $message = "Adresse email invalide.";
    } else {
        // Recherche de l'utilisateur dans la base de données
        $sql = "SELECT * FROM utilisateur WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie, enregistrement de l'utilisateur dans la session
            $_SESSION['user_id'] = $user['username'];  // Utiliser l'email comme identifiant unique
            $_SESSION['role_id'] = $user['role_id'];   // Stocker le rôle (1 pour admin, etc.)

            // Redirection vers la page d'accueil ou page spécifique pour l'administrateur
            if ($user['role_id'] == 1) {
                header('Location: ../index.php');  // Page spécifique pour l'admin
            } else {
                header('Location: ../index.php');  // Page pour les utilisateurs normaux
            }
            exit;
        } else {
            $message = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Arcadia</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="icon" href="data:,">
</head>
<body>
    <header>
        <h1>Arcadia - Se connecter</h1>
    </header>
    <nav>
        <a href="../index.php">Accueil</a>
        <a href="../services/services.php">Services</a>
        <a href="../habitats/habitats.php">Habitats</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['role_id'] == 2): ?>
                <a href="../habitats/gestion_habitats.php">Gestion des Habitats et des Animaux</a>
                <a href="../connect-disconnect/logout.php">Se déconnecter</a>
            <?php endif; ?>
            <?php if ($_SESSION['role_id'] == 3): ?>
                <a href="../avis/gestion_avis.php">Gestion des Avis</a>
                <a href="../habitats/gestion_habitats.php">Gestion des Habitats et des Animaux</a>
                <a href="../connect-disconnect/logout.php">Se déconnecter</a>
            <?php endif; ?>
            <?php if ($_SESSION['role_id'] == 1): ?>
                <a href="../avis/gestion_avis.php">Gestion des Avis</a>
                <a href="../services/gestion_services.php">Gestion des Services</a>
                <a href="../habitats/gestion_habitats.php">Gestion des Habitats et des Animaux</a>
                <a href="../admin/create_user.php">Créer un Utilisateur</a>
                <a href="../connect-disconnect/logout.php">Se déconnecter</a>
            <?php endif; ?>
            <?php else: ?>
                <a href="../connect-disconnect/login.php">Se connecter</a>
        <?php endif; ?>
    </nav>
    <main>
        <h2>Seul les Employées peuvent se connecter :</h2>
        <section class="form-section">
            <form method="POST" action="login.php">
                <label for="username">Adresse email :</label>
                <input type="email" id="username" name="username" required>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Connexion</button>
            </form>
            <?php if (isset($message)): ?>
                <p><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>&copy; Arcadia Zoo</p>
    </footer>
</body>
</html>
